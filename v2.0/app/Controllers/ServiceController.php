<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Response;
use App\Models\ServiceCategory;
use App\Models\SubService;

final class ServiceController extends Controller
{
    public function __construct(
        private readonly ServiceCategory $categories = new ServiceCategory(),
        private readonly SubService $subServices = new SubService()
    ) {
    }

    public function index(): void
    {
        $this->view('public.services', [
            'categories' => $this->categories->activeWithMinPrice(),
        ]);
    }

    public function showCategory(string $id): void
    {
        $category = $this->categories->findActive((int) $id);

        if ($category === null) {
            Response::notFound();
            return;
        }

        $this->view('public.service-detail', [
            'category' => $category,
            'subServices' => $this->subServices->activeByCategory((int) $id),
        ]);
    }

    public function showSubService(string $id): void
    {
        $subService = $this->subServices->findActive((int) $id);

        if ($subService === null) {
            Response::notFound();
            return;
        }

        $this->view('public.sub-service-detail', [
            'subService' => $subService,
        ]);
    }

    public function pricing(): void
    {
        $this->view('public.pricing', [
            'subServices' => $this->subServices->pricing(),
        ]);
    }
}
