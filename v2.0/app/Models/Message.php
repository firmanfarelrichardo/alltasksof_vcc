<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Message
{
    public function recentForConsultation(int $consultationId, int $limit = 50): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT m.id, m.sender_id, u.name AS sender_name, u.role AS sender_role, m.message, m.created_at
             FROM messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.consultation_id = :consultation_id
             ORDER BY m.id DESC
             LIMIT :limit'
        );
        $stmt->bindValue('consultation_id', $consultationId, PDO::PARAM_INT);
        $stmt->bindValue('limit', $limit, PDO::PARAM_INT);
        $stmt->execute();

        return array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function afterId(int $consultationId, int $afterId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT m.id, m.sender_id, u.name AS sender_name, u.role AS sender_role, m.message, m.created_at
             FROM messages m
             JOIN users u ON u.id = m.sender_id
             WHERE m.consultation_id = :consultation_id AND m.id > :after_id
             ORDER BY m.id ASC
             LIMIT 50'
        );
        $stmt->bindValue('consultation_id', $consultationId, PDO::PARAM_INT);
        $stmt->bindValue('after_id', $afterId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $consultationId, int $senderId, string $message): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO messages (consultation_id, sender_id, message)
             VALUES (:consultation_id, :sender_id, :message)'
        );
        $stmt->execute([
            'consultation_id' => $consultationId,
            'sender_id' => $senderId,
            'message' => $message,
        ]);

        return (int) Database::connection()->lastInsertId();
    }
}
