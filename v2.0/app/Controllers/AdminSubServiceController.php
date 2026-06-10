<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\SubService;
use App\Services\ServiceManagementService;

final class AdminSubServiceController extends Controller
{
    public function __construct(
        private readonly SubService $subServices = new SubService(),
        private readonly ServiceManagementService $service = new ServiceManagementService()
    ) {
    }

    public function index(): void
    {
        $this->view('admin.sub-services', [
            'subServices' => $this->subServices->assignedToAdmin((int) Session::get('user_id')),
        ]);
    }

    public function edit(string $id): void
    {
        $subService = $this->subServices->findForAdmin((int) $id, (int) Session::get('user_id'));

        if ($subService === null) {
            Response::forbidden();
            return;
        }

        $this->view('admin.sub-service-form', ['subService' => $subService]);
    }

    public function update(string $id): void
    {
        $result = $this->service->updateAdminPrice((int) $id, (int) Session::get('user_id'), $_POST);
        Session::flash($result['success'] ? 'success' : 'error', $result['message']);
        Response::redirect('/admin/sub-services');
    }
}
