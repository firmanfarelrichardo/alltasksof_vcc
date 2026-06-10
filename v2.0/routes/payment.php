<?php

declare(strict_types=1);

use App\Controllers\MidtransWebhookController;
use App\Controllers\PaymentController;
use App\Middleware\ApprovedUserMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;

$router->get('/user/consultations/{id}/payment', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new PaymentController())->show($id);
});

$router->post('/user/consultations/{id}/payment', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new PaymentController())->create($id);
});

$router->get('/user/payments/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new PaymentController())->showStatus($id);
});

$router->get('/api/user/payments/{id}/status', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new PaymentController())->statusApi($id);
});

$router->post('/user/payments/{id}/refresh-status', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new PaymentController())->refreshStatus($id);
});

$router->post('/payments/midtrans/notification', function (): void {
    (new MidtransWebhookController())->notification();
});

$router->get('/payments/midtrans/finish', function (): void {
    (new PaymentController())->finish();
});

$router->get('/payments/midtrans/unfinish', function (): void {
    (new PaymentController())->unfinish();
});

$router->get('/payments/midtrans/error', function (): void {
    (new PaymentController())->error();
});
