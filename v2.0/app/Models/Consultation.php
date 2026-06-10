<?php

declare(strict_types=1);

namespace App\Models;

use App\Core\Database;
use PDO;

final class Consultation
{
    public function create(int $userId, int $subServiceId): int
    {
        $stmt = Database::connection()->prepare(
            'INSERT INTO consultations (user_id, sub_service_id, status)
             VALUES (:user_id, :sub_service_id, "waiting_payment")'
        );
        $stmt->execute([
            'user_id' => $userId,
            'sub_service_id' => $subServiceId,
        ]);

        return (int) Database::connection()->lastInsertId();
    }

    public function userHistory(int $userId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT c.id, c.status, c.created_at, ss.name AS sub_service_name, sc.name AS category_name,
                    p.id AS payment_id, p.internal_status, p.amount
             FROM consultations c
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE c.user_id = :user_id
             ORDER BY c.created_at DESC, c.id DESC'
        );
        $stmt->execute(['user_id' => $userId]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findForUser(int $id, int $userId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT c.id, c.user_id, c.sub_service_id, c.status, c.created_at,
                    ss.name AS sub_service_name, ss.price AS current_price,
                    sc.name AS category_name,
                    p.id AS payment_id, p.order_id, p.amount, p.internal_status
             FROM consultations c
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE c.id = :id AND c.user_id = :user_id
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function pipelineForAdmin(int $adminId, string $type, ?string $search, int $limit, int $offset): array
    {
        [$where, $params] = $this->pipelineWhere($type, $search);
        $params['admin_id'] = $adminId;
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        $stmt = Database::connection()->prepare(
            'SELECT c.id, c.status, c.created_at, u.name AS user_name, u.email AS user_email,
                    ss.name AS sub_service_name, sc.name AS category_name,
                    p.id AS payment_id, p.order_id, p.amount, p.internal_status, p.transaction_status
             FROM consultations c
             JOIN users u ON u.id = c.user_id
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             JOIN admin_service_assignments asa ON asa.service_category_id = sc.id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE asa.admin_id = :admin_id ' . $where . '
             ORDER BY c.updated_at DESC, c.id DESC
             LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, in_array($key, ['limit', 'offset', 'admin_id'], true) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPipelineForAdmin(int $adminId, string $type, ?string $search): int
    {
        [$where, $params] = $this->pipelineWhere($type, $search);
        $params['admin_id'] = $adminId;

        $stmt = Database::connection()->prepare(
            'SELECT COUNT(*) AS total
             FROM consultations c
             JOIN users u ON u.id = c.user_id
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             JOIN admin_service_assignments asa ON asa.service_category_id = sc.id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE asa.admin_id = :admin_id ' . $where
        );
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function summaryForAdmin(int $adminId): array
    {
        $stmt = Database::connection()->prepare(
            'SELECT
                SUM(CASE WHEN p.internal_status = "pending" THEN 1 ELSE 0 END) AS pending,
                SUM(CASE WHEN p.internal_status = "cancelled" THEN 1 ELSE 0 END) AS cancelled,
                SUM(CASE WHEN p.internal_status = "paid" THEN 1 ELSE 0 END) AS paid,
                SUM(CASE WHEN c.status = "active" THEN 1 ELSE 0 END) AS active,
                SUM(CASE WHEN c.status = "closed" THEN 1 ELSE 0 END) AS closed
             FROM consultations c
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN admin_service_assignments asa ON asa.service_category_id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE asa.admin_id = :admin_id'
        );
        $stmt->execute(['admin_id' => $adminId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    public function pipelineForSuperadmin(string $type, ?string $search, int $limit, int $offset): array
    {
        [$where, $params] = $this->pipelineWhere($type, $search);
        $params['limit'] = $limit;
        $params['offset'] = $offset;

        $stmt = Database::connection()->prepare(
            'SELECT c.id, c.status, c.created_at, u.name AS user_name, u.email AS user_email,
                    ss.name AS sub_service_name, sc.name AS category_name,
                    p.id AS payment_id, p.order_id, p.amount, p.internal_status, p.transaction_status
             FROM consultations c
             JOIN users u ON u.id = c.user_id
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE 1=1 ' . $where . '
             ORDER BY c.updated_at DESC, c.id DESC
             LIMIT :limit OFFSET :offset'
        );
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value, in_array($key, ['limit', 'offset'], true) ? PDO::PARAM_INT : PDO::PARAM_STR);
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function countPipelineForSuperadmin(string $type, ?string $search): int
    {
        [$where, $params] = $this->pipelineWhere($type, $search);
        $stmt = Database::connection()->prepare(
            'SELECT COUNT(*) AS total
             FROM consultations c
             JOIN users u ON u.id = c.user_id
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE 1=1 ' . $where
        );
        $stmt->execute($params);

        return (int) $stmt->fetchColumn();
    }

    public function findForAdmin(int $id, int $adminId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT c.id, c.user_id, c.sub_service_id, c.status, c.created_at,
                    u.name AS user_name, u.email AS user_email,
                    ss.name AS sub_service_name, sc.name AS category_name,
                    p.id AS payment_id, p.internal_status
             FROM consultations c
             JOIN users u ON u.id = c.user_id
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             JOIN admin_service_assignments asa ON asa.service_category_id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE c.id = :id AND asa.admin_id = :admin_id
             ORDER BY p.created_at DESC, p.id DESC
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'admin_id' => $adminId]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function chatForUser(int $id, int $userId): ?array
    {
        $stmt = Database::connection()->prepare(
            'SELECT c.id, c.user_id, c.sub_service_id, c.status, c.created_at,
                    ss.name AS sub_service_name, sc.name AS category_name,
                    p.id AS payment_id, p.internal_status
             FROM consultations c
             JOIN sub_services ss ON ss.id = c.sub_service_id
             JOIN service_categories sc ON sc.id = ss.service_category_id
             LEFT JOIN payments p ON p.consultation_id = c.id
             WHERE c.id = :id AND c.user_id = :user_id
             ORDER BY p.created_at DESC, p.id DESC
             LIMIT 1'
        );
        $stmt->execute(['id' => $id, 'user_id' => $userId]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = Database::connection()->prepare(
            'UPDATE consultations SET status = :status WHERE id = :id'
        );
        $stmt->execute(['id' => $id, 'status' => $status]);

        return $stmt->rowCount() >= 0;
    }

    private function pipelineWhere(string $type, ?string $search): array
    {
        $where = match ($type) {
            'cancelled' => ' AND (p.internal_status = "cancelled" OR c.status = "cancelled")',
            'paid' => ' AND p.internal_status = "paid"',
            'active' => ' AND c.status = "active"',
            'closed' => ' AND c.status = "closed"',
            default => ' AND p.internal_status = "pending"',
        };

        $params = [];
        if ($search !== null && trim($search) !== '') {
            $where .= ' AND (u.name LIKE :search_user_name OR u.email LIKE :search_user_email OR ss.name LIKE :search_sub_service OR sc.name LIKE :search_category)';
            $keyword = '%' . trim($search) . '%';
            $params['search_user_name'] = $keyword;
            $params['search_user_email'] = $keyword;
            $params['search_sub_service'] = $keyword;
            $params['search_category'] = $keyword;
        }

        return [$where, $params];
    }
}
