<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class SubService
{
    public function activeByCategory(int $categoryId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, service_category_id, name, slug, description, price
             FROM sub_services
             WHERE service_category_id = :category_id AND is_active = 1
             ORDER BY price ASC, id ASC'
        );
        $stmt->execute(['category_id' => $categoryId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findActive(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT ss.id, ss.service_category_id, ss.name, ss.slug, ss.description, ss.price, sc.name AS category_name
             FROM sub_services ss
             JOIN service_categories sc ON sc.id = ss.service_category_id
             WHERE ss.id = :id AND ss.is_active = 1 AND sc.is_active = 1
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function pricing(): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT ss.id, ss.name, ss.price, sc.name AS category_name
             FROM sub_services ss
             JOIN service_categories sc ON sc.id = ss.service_category_id
             WHERE ss.is_active = 1 AND sc.is_active = 1
             ORDER BY sc.id ASC, ss.price ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function allWithCategory(): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT ss.id, ss.service_category_id, ss.name, ss.slug, ss.description, ss.price, ss.is_active, sc.name AS category_name
             FROM sub_services ss
             JOIN service_categories sc ON sc.id = ss.service_category_id
             ORDER BY sc.id ASC, ss.id ASC'
        );
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function assignedToAdmin(int $adminId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT ss.id, ss.service_category_id, ss.name, ss.slug, ss.description, ss.price, ss.is_active, sc.name AS category_name
             FROM sub_services ss
             JOIN service_categories sc ON sc.id = ss.service_category_id
             JOIN admin_service_assignments asa ON asa.service_category_id = sc.id
             WHERE asa.admin_id = :admin_id
             ORDER BY sc.name ASC, ss.name ASC'
        );
        $stmt->execute(['admin_id' => $adminId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT id, service_category_id, name, slug, description, price, is_active
             FROM sub_services
             WHERE id = :id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function findForAdmin(int $id, int $adminId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT ss.id, ss.service_category_id, ss.name, ss.slug, ss.description, ss.price, ss.is_active, sc.name AS category_name
             FROM sub_services ss
             JOIN service_categories sc ON sc.id = ss.service_category_id
             JOIN admin_service_assignments asa ON asa.service_category_id = sc.id
             WHERE ss.id = :id AND asa.admin_id = :admin_id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'admin_id' => $adminId]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function create(int $categoryId, string $name, string $slug, ?string $description, float $price, int $isActive): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO sub_services (service_category_id, name, slug, description, price, is_active)
             VALUES (:category_id, :name, :slug, :description, :price, :is_active)'
        );
        $stmt->execute([
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'is_active' => $isActive,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function update(int $id, int $categoryId, string $name, string $slug, ?string $description, float $price, int $isActive): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE sub_services
             SET service_category_id = :category_id, name = :name, slug = :slug, description = :description, price = :price, is_active = :is_active
             WHERE id = :id'
        );
        $stmt->execute([
            'id' => $id,
            'category_id' => $categoryId,
            'name' => $name,
            'slug' => $slug,
            'description' => $description,
            'price' => $price,
            'is_active' => $isActive,
        ]);

        return $stmt->rowCount() >= 0;
    }

    public function updateAdminPrice(int $id, int $adminId, float $price): bool
    {
        if ($this->findForAdmin($id, $adminId) === null) {
            return false;
        }

        $stmt = Database::connection()->prepare(
            'UPDATE sub_services SET price = :price WHERE id = :id'
        );
        $stmt->execute(['id' => $id, 'price' => $price]);

        return $stmt->rowCount() >= 0;
    }

    public function deactivate(int $id): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE sub_services SET is_active = 0 WHERE id = :id'
        );
        $stmt->execute(['id' => $id]);

        return $stmt->rowCount() > 0;
    }
}
