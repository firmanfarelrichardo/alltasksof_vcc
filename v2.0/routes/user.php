<?php

declare(strict_types=1);

use App\Controllers\UserDashboardController;
use App\Controllers\ChatController;
use App\Controllers\ConsultationController;
use App\Middleware\ApprovedUserMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;

$router->get('/user/dashboard', function (): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new UserDashboardController())->index();
});

$router->get('/user/consultations', function (): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new ConsultationController())->userHistory();
});

$router->post('/user/consultations', function (): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new ConsultationController())->create();
});

$router->get('/user/consultations/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new ConsultationController())->showForUser($id);
});

$router->get('/user/consultations/{id}/chat', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new ChatController())->showForUser($id);
});

$router->get('/api/user/consultations/{id}/messages', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle()) {
        return;
    }

    (new ChatController())->messagesForUser($id);
});

$router->post('/api/user/consultations/{id}/messages', function (string $id): void {
    if (!AuthMiddleware::handle() || !ApprovedUserMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new ChatController())->sendByUserApi($id);
});
