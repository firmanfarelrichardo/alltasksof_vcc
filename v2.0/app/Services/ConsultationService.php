<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Database;
use App\Models\Consultation;
use App\Models\SubService;
use Throwable;

final class ConsultationService
{
    public function __construct(
        private readonly Consultation $consultations = new Consultation(),
        private readonly SubService $subServices = new SubService()
    ) {
    }

    public function createWaitingPayment(int $userId, int $subServiceId): ?int
    {
        $subService = $this->subServices->findActive($subServiceId);
        if ($subService === null) {
            return null;
        }

        $pdo = Database::connection();
        $pdo->beginTransaction();

        try {
            $consultationId = $this->consultations->create($userId, $subServiceId);
            $pdo->commit();

            return $consultationId;
        } catch (Throwable $exception) {
            $pdo->rollBack();
            throw $exception;
        }
    }

    public function closeForAdmin(int $consultationId, int $adminId): bool
    {
        $consultation = $this->consultations->findForAdmin($consultationId, $adminId);
        if ($consultation === null || $consultation['status'] !== 'active') {
            return false;
        }

        return $this->consultations->updateStatus($consultationId, 'closed');
    }
}
