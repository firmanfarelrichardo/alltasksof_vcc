<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Core\Session;
use App\Models\ServiceCategory;
use App\Models\SubService;
use App\Services\ServiceManagementService;

final class SuperadminServiceController extends Controller
{
    public function __construct(
        private readonly ServiceCategory $categories = new ServiceCategory(),
        private readonly SubService $subServices = new SubService(),
        private readonly ServiceManagementService $service = new ServiceManagementService()
    ) {
    }

    public function index(): void
    {
        $this->view('superadmin.services', ['categories' => $this->categories->all()]);
    }

    public function create(): void
    {
        $this->view('superadmin.service-form', ['category' => null]);
    }

    public function store(): void
    {
        $result = $this->service->saveCategory(null, $_POST);
        Session::flash($result['success'] ? 'success' : 'error', $result['message']);
        Response::redirect($result['success'] ? '/superadmin/services' : '/superadmin/services/create');
    }

    public function edit(string $id): void
    {
        $category = $this->categories->find((int) $id);

        if ($category === null) {
            Response::notFound();
            return;
        }

        $this->view('superadmin.service-form', ['category' => $category]);
    }

    public function update(string $id): void
    {
        $result = $this->service->saveCategory((int) $id, $_POST);
        Session::flash($result['success'] ? 'success' : 'error', $result['message']);
        Response::redirect('/superadmin/services');
    }

    public function destroy(string $id): void
    {
        $this->categories->deactivate((int) $id);
        Session::flash('success', 'Kategori layanan dinonaktifkan.');
        Response::redirect('/superadmin/services');
    }

    public function subServices(): void
    {
        $this->view('superadmin.sub-services', ['subServices' => $this->subServices->allWithCategory()]);
    }

    public function createSubService(): void
    {
        $this->view('superadmin.sub-service-form', [
            'subService' => null,
            'categories' => $this->categories->all(),
        ]);
    }

    public function storeSubService(): void
    {
        $result = $this->service->saveSubService(null, $_POST);
        Session::flash($result['success'] ? 'success' : 'error', $result['message']);
        Response::redirect($result['success'] ? '/superadmin/sub-services' : '/superadmin/sub-services/create');
    }

    public function editSubService(string $id): void
    {
        $subService = $this->subServices->find((int) $id);

        if ($subService === null) {
            Response::notFound();
            return;
        }

        $this->view('superadmin.sub-service-form', [
            'subService' => $subService,
            'categories' => $this->categories->all(),
        ]);
    }

    public function updateSubService(string $id): void
    {
        $result = $this->service->saveSubService((int) $id, $_POST);
        Session::flash($result['success'] ? 'success' : 'error', $result['message']);
        Response::redirect('/superadmin/sub-services');
    }

    public function destroySubService(string $id): void
    {
        $this->subServices->deactivate((int) $id);
        Session::flash('success', 'Sub layanan dinonaktifkan.');
        Response::redirect('/superadmin/sub-services');
    }
}
