<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Services\ApprovalService;

final class SuperadminUserApprovalController extends Controller
{
    public function __construct(private readonly ApprovalService $approvalService = new ApprovalService())
    {
    }

    public function pending(): void
    {
        $this->view('superadmin.user-approval', [
            'users' => $this->approvalService->pendingUsers(),
        ]);
    }

    public function approve(string $id): void
    {
        $this->approvalService->approveUser((int) $id);
        Session::flash('success', 'User berhasil disetujui.');
        Response::redirect('/superadmin/users/pending');
    }

    public function reject(string $id): void
    {
        $this->approvalService->rejectUser((int) $id);
        Session::flash('success', 'User berhasil ditolak.');
        Response::redirect('/superadmin/users/pending');
    }
}
