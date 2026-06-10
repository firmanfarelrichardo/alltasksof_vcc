<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class ServiceCategory
{
    public function activeWithMinPrice(): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT sc.id, sc.name, sc.slug, sc.description, MIN(ss.price) AS min_price
             FROM service_categories sc
             LEFT JOIN sub_services ss ON ss.service_category_id = sc.id AND ss.is_active = 1
             WHERE sc.is_active = 1
             GROUP BY sc.id, sc.name, sc.slug, sc.description
             ORDER BY sc.id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function all(): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, slug, description, is_active, created_at
             FROM service_categories
             ORDER BY id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, slug, description, is_active
             FROM service_categories
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function findActive(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, name, slug, description
             FROM service_categories
             WHERE id = :id AND is_active = 1
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function create(string $name, string $slug, ?string $description, int $isActive): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO service_categories (name, slug, description, is_active)
             VALUES (:name, :slug, :description, :is_active)'
        );
        $stmt->execute([
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $isActive,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function update(int $id, string $name, string $slug, ?string $description, int $isActive): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE service_categories
             SET name = :name, slug = :slug, description = :description, is_active = :is_active
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'is_active' => $isActive,
        ]);

        return $stmt->rowCount() >= 0;
    }

    public function deactivate(int $id): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE service_categories SET is_active = 0 WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
