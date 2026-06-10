<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\AdminServiceAssignment;
use App\Models\ServiceCategory;
use App\Models\User;

final class SuperadminAdminManagementController extends Controller
{
    public function __construct(
        private readonly User $users = new User(),
        private readonly ServiceCategory $categories = new ServiceCategory(),
        private readonly AdminServiceAssignment $assignments = new AdminServiceAssignment()
    ) {
    }

    public function index(): void
    {
        $this->view('superadmin.admins', ['admins' => $this->users->approvedAdmins()]);
    }

    public function assignments(string $id): void
    {
        $admin = $this->users->findById((int) $id);

        if ($admin === null || $admin['role'] !== 'admin') {
            Response::notFound();
            return;
        }

        $this->view('superadmin.admin-assignments', [
            'admin' => $admin,
            'assignments' => $this->assignments->forAdmin((int) $id),
            'categories' => $this->categories->all(),
        ]);
    }

    public function storeAssignment(string $id): void
    {
        $serviceCategoryId = (int) ($_POST['service_category_id'] ?? 0);

        if ($serviceCategoryId > 0) {
            $this->assignments->create((int) $id, $serviceCategoryId);
            Session::flash('success', 'Assignment admin berhasil disimpan.');
        }

        Response::redirect('/superadmin/admins/' . $id . '/assignments');
    }

    public function destroyAssignment(string $adminId, string $assignmentId): void
    {
        $this->assignments->deleteForAdmin((int) $assignmentId, (int) $adminId);
        Session::flash('success', 'Assignment admin berhasil dihapus.');
        Response::redirect('/superadmin/admins/' . $adminId . '/assignments');
    }
}
