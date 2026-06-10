<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Payment
{
    public function createMidtransPending(int $userId, int $consultationId, int $subServiceId, string $orderId, float $amount): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO payments (user_id, consultation_id, sub_service_id, order_id, amount, provider, internal_status)
             VALUES (:user_id, :consultation_id, :sub_service_id, :order_id, :amount, "midtrans", "pending")'
        );
        $stmt->execute([
            'user_id' => $userId,
            'consultation_id' => $consultationId,
            'sub_service_id' => $subServiceId,
            'order_id' => $orderId,
            'amount' => $amount,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function setSnapToken(int $id, string $snapToken): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE payments SET snap_token = :snap_token WHERE id = :id'
        );
        $stmt->execute(['id' => $id, 'snap_token' => $snapToken]);

        return $stmt->rowCount() >= 0;
    }

    public function findForUser(int $id, int $userId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.id, p.user_id, p.consultation_id, p.sub_service_id, p.order_id, p.amount, p.provider,
                    p.snap_token, p.payment_type, p.transaction_id, p.transaction_status, p.fraud_status,
                    p.internal_status, p.paid_at, p.created_at, p.updated_at,
                    c.status AS consultation_status, ss.name AS sub_service_name, sc.name AS category_name
             FROM payments p
             JOIN consultations c ON c.id = p.consultation_id
             JOIN sub_services ss ON ss.id = p.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             WHERE p.id = :id AND p.user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function latestForConsultationUser(int $consultationId, int $userId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.id, p.user_id, p.consultation_id, p.sub_service_id, p.order_id, p.amount, p.provider,
                    p.snap_token, p.payment_type, p.transaction_id, p.transaction_status, p.fraud_status,
                    p.internal_status, p.paid_at, p.created_at, p.updated_at,
                    c.status AS consultation_status, ss.name AS sub_service_name, sc.name AS category_name
             FROM payments p
             JOIN consultations c ON c.id = p.consultation_id
             JOIN sub_services ss ON ss.id = p.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             WHERE p.consultation_id = :consultation_id AND p.user_id = :user_id
             ORDER BY p.created_at DESC, p.id DESC
             LIMIT 1'
        );
        $stmt->execute(['consultation_id' => $consultationId, 'user_id' => $userId]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function findByOrderId(string $orderId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT p.*, c.status AS consultation_status
             FROM payments p
             JOIN consultations c ON c.id = p.consultation_id
             WHERE p.order_id = :order_id
             LIMIT 1'
        );
        $stmt->execute(['order_id' => $orderId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row === false ? null : $row;
    }

    public function updateMidtransStatus(int $id, array $payload, string $internalStatus): bool
    {
        $paidAtSql = $internalStatus === 'paid' ? ', paid_at = COALESCE(paid_at, CURRENT_TIMESTAMP)' : '';

        $stmt = Database::connection()->prepare(
            'UPDATE payments
             SET payment_type = :payment_type,
                 transaction_id = :transaction_id,
                 transaction_status = :transaction_status,
                 fraud_status = :fraud_status,
                 internal_status = :internal_status' . $paidAtSql . '
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'payment_type' => $payload['payment_type'] ?? null,
            'transaction_id' => $payload['transaction_id'] ?? null,
            'transaction_status' => $payload['transaction_status'] ?? null,
            'fraud_status' => $payload['fraud_status'] ?? null,
            'internal_status' => $internalStatus,
        ]);

        return $stmt->rowCount() >= 0;
    }
}
