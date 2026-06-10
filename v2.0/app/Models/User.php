<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class User
{
    public function findByEmail(string $email): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, email, password, role, status
             FROM users
             WHERE email = :email
             LIMIT 1'
        );
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    public function findById(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, email, role, status, created_at
             FROM users
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch();

        return $user === false ? null : $user;
    }

    public function createPendingUser(string $name, string $email, string $passwordHash): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO users (name, email, password, role, status)
             VALUES (:name, :email, :password, "user", "pending")'
        );
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $passwordHash,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function pendingUsers(): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, email, status, created_at
             FROM users
             WHERE role = "user" AND status = "pending"
             ORDER BY created_at ASC, id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE users
             SET status = :status
             WHERE id = :id AND role = "user"'
        );
        $stmt->execute([
            'id' => $id,
            'status' => $status,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function approvedAdmins(): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, email
             FROM users
             WHERE role = "admin" AND status = "approved"
             ORDER BY name ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
