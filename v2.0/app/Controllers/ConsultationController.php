<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\Consultation;
use App\Services\ConsultationService;

final class ConsultationController extends Controller
{
    public function __construct(
        private readonly Consultation $consultations = new Consultation(),
        private readonly ConsultationService $service = new ConsultationService()
    ) {
    }

    public function create(): void
    {
        $subServiceId = (int) ($_POST['sub_service_id'] ?? 0);
        $consultationId = $this->service->createWaitingPayment((int) Session::get('user_id'), $subServiceId);

        if ($consultationId === null) {
            Response::notFound();
            return;
        }

        Session::flash('success', 'Konsultasi dibuat. Silakan lanjutkan pembayaran Midtrans.');
        Response::redirect('/user/consultations/' . $consultationId . '/payment');
    }

    public function userHistory(): void
    {
        $this->view('user.consultations', [
            'consultations' => $this->consultations->userHistory((int) Session::get('user_id')),
        ]);
    }

    public function showForUser(string $id): void
    {
        $consultation = $this->consultations->findForUser((int) $id, (int) Session::get('user_id'));

        if ($consultation === null) {
            Response::notFound();
            return;
        }

        $this->view('user.consultation-detail', ['consultation' => $consultation]);
    }

    public function closeForAdmin(string $id): void
    {
        if (!$this->service->closeForAdmin((int) $id, (int) Session::get('user_id'))) {
            Response::forbidden();
            return;
        }

        Session::flash('success', 'Konsultasi ditutup.');
        Response::redirect('/admin/pipeline/consultations/closed');
    }

    public function superadminIndex(): void
    {
        $type = $this->pipelineType((string) ($_GET['tab'] ?? 'pending'));
        $search = trim((string) ($_GET['q'] ?? ''));
        $page = max(1, (int) ($_GET['page'] ?? 1));
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $this->view('superadmin.consultations', [
            'type' => $type,
            'search' => $search,
            'page' => $page,
            'total' => $this->consultations->countPipelineForSuperadmin($type, $search !== '' ? $search : null),
            'limit' => $limit,
            'items' => $this->consultations->pipelineForSuperadmin($type, $search !== '' ? $search : null, $limit, $offset),
        ]);
    }

    private function pipelineType(string $type): string
    {
        return in_array($type, ['pending', 'cancelled', 'paid', 'active', 'closed'], true) ? $type : 'pending';
    }
}
