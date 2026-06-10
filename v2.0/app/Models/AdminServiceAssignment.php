<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class AdminServiceAssignment
{
    public function forAdmin(int $adminId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT asa.id, asa.admin_id, asa.service_category_id, sc.name AS service_name
             FROM admin_service_assignments asa
             JOIN service_categories sc ON sc.id = asa.service_category_id
             WHERE asa.admin_id = :admin_id
             ORDER BY sc.name ASC'
        );
        $stmt->execute(['admin_id' => $adminId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(int $adminId, int $serviceCategoryId): bool
    {
        $stmt = Database::connection()->prepare(
            'INSERT IGNORE INTO admin_service_assignments (admin_id, service_category_id)
             VALUES (:admin_id, :service_category_id)'
        );
        $stmt->execute([
            'admin_id' => $adminId,
            'service_category_id' => $serviceCategoryId,
        ]);

        return $stmt->rowCount() >= 0;
    }

    public function deleteForAdmin(int $assignmentId, int $adminId): bool
    {
        $stmt = Database::connection()->prepare(
            'DELETE FROM admin_service_assignments
             WHERE id = :id AND admin_id = :admin_id'
        );
        $stmt->execute([
            'id' => $assignmentId,
            'admin_id' => $adminId,
        ]);

        return $stmt->rowCount() > 0;
    }
}
