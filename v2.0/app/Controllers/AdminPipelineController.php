<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Session;
use App\Models\Consultation;

final class AdminPipelineController extends Controller
{
    public function __construct(private readonly Consultation $consultations = new Consultation())
    {
    }

    public function index(): void
    {
        $this->render('pending');
    }

    public function pendingPayments(): void
    {
        $this->render('pending');
    }

    public function cancelledPayments(): void
    {
        $this->render('cancelled');
    }

    public function successfulPayments(): void
    {
        $this->render('paid');
    }

    public function activeConsultations(): void
    {
        $this->render('active');
    }

    public function closedConsultations(): void
    {
        $this->render('closed');
    }

    private function render(string $type): void
    {
        $adminId = (int) Session::get('user_id');
        $search = trim((string) ($_GET['q'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $this->view('admin.pipeline', [
            'type' => $type,
            'search' => $search,
            'page' => $page,
            'limit' => $limit,
            'summary' => $this->consultations->summaryForAdmin($adminId),
            'total' => $this->consultations->countPipelineForAdmin($adminId, $type, $search !== '' ? $search : null),
            'items' => $this->consultations->pipelineForAdmin($adminId, $type, $search !== '' ? $search : null, $limit, $offset),
        ]);
    }
}
