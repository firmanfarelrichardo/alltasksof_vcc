<?php

declare(strict_types=1);

use App\Core\Database;
use App\Core\Env;
use App\Core\Response;
use App\Controllers\ServiceController;

$router->get('/', function (): void {
    require BASE_PATH . '/app/Views/public/home.php';
});

$router->get('/services', function (): void {
    (new ServiceController())->index();
});

$router->get('/services/{id}', function (string $id): void {
    (new ServiceController())->showCategory($id);
});

$router->get('/sub-services/{id}', function (string $id): void {
    (new ServiceController())->showSubService($id);
});

$router->get('/pricing', function (): void {
    (new ServiceController())->pricing();
});

$router->get('/_dev/db-check', function (): void {
    if (Env::get('APP_ENV', 'development') !== 'development') {
        Response::notFound();
        return;
    }

    try {
        $services = Database::connection()
            ->query('SELECT id, name, slug FROM service_categories WHERE is_active = 1 ORDER BY id')
            ->fetchAll();

        Response::json([
            'success' => true,
            'message' => 'Koneksi database lokal berhasil.',
            'data' => [
                'service_categories' => $services,
            ],
        ]);
    } catch (\Throwable) {
        Response::json([
            'success' => false,
            'message' => 'Koneksi database lokal gagal.',
        ], 500);
    }
});
