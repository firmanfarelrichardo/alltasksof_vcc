<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Consultation;
use App\Models\Payment;
use App\Models\SubService;
use App\Models\User;
use RuntimeException;
use Throwable;

final class PaymentService
{
    public function __construct(
        private readonly Payment $payments = new Payment(),
        private readonly Consultation $consultations = new Consultation(),
        private readonly SubService $subServices = new SubService(),
        private readonly User $users = new User(),
        private readonly MidtransService $midtrans = new MidtransService()
    ) {
    }

    public function createSnapPayment(int $consultationId, int $userId): ?array
    {
        $consultation = $this->consultations->findForUser($consultationId, $userId);
        if ($consultation === null || $consultation['status'] !== 'waiting_payment') {
            return null;
        }

        $latestPayment = $this->payments->latestForConsultationUser($consultationId, $userId);
        if ($latestPayment !== null && $latestPayment['internal_status'] === 'pending' && !empty($latestPayment['snap_token'])) {
            return $latestPayment;
        }

        $subService = $this->subServices->findActive((int) $consultation['sub_service_id']);
        $user = $this->users->findById($userId);
        if ($subService === null || $user === null) {
            return null;
        }

        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $orderId = $this->makeOrderId($consultationId);
            $amount = (float) $subService['price'];
            $paymentId = $this->payments->createMidtransPending($userId, $consultationId, (int) $subService['id'], $orderId, $amount);
            $payment = [
                'id' => $paymentId,
                'order_id' => $orderId,
                'amount' => $amount,
            ];
            $snapToken = $this->midtrans->createSnapToken($payment, $user, $subService);
            $this->payments->setSnapToken($paymentId, $snapToken);
            $pdo->commit();

            return $this->payments->findForUser($paymentId, $userId);
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function applyWebhookPayload(array $payload, bool $verifySignature = true): ?array
    {
        if ($verifySignature && !$this->midtrans->verifySignature($payload)) {
            throw new RuntimeException('Invalid Midtrans signature.');
        }

        $orderId = (string) ($payload['order_id'] ?? '');
        if ($orderId === '') {
            return null;
        }

        $payment = $this->payments->findByOrderId($orderId);
        if ($payment === null) {
            return null;
        }

        $internalStatus = $this->midtrans->mapInternalStatus($payload);
        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $this->payments->updateMidtransStatus((int) $payment['id'], $payload, $internalStatus);

            if ($internalStatus === 'paid' && $payment['consultation_status'] !== 'active') {
                $this->consultations->updateStatus((int) $payment['consultation_id'], 'active');
            }

            if (in_array($internalStatus, ['cancelled', 'failed', 'expired'], true) && $payment['consultation_status'] === 'waiting_payment') {
                $this->consultations->updateStatus((int) $payment['consultation_id'], 'cancelled');
            }

            $pdo->commit();

            return $this->payments->findByOrderId($orderId);
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function refreshStatus(int $paymentId, int $userId): ?array
    {
        $payment = $this->payments->findForUser($paymentId, $userId);
        if ($payment === null) {
            return null;
        }

        $payload = $this->midtrans->status((string) $payment['order_id']);

        return $this->applyWebhookPayload($payload, false);
    }

    private function makeOrderId(int $consultationId): string
    {
        return 'CONSULT-' . $consultationId . '-' . time() . '-' . random_int(100, 999);
    }
}
