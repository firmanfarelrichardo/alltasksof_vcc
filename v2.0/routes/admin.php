<?php

declare(strict_types=1);

use App\Controllers\AdminDashboardController;
use App\Controllers\AdminPipelineController;
use App\Controllers\AdminSubServiceController;
use App\Controllers\ChatController;
use App\Controllers\ConsultationController;
use App\Middleware\AdminMiddleware;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;

$router->get('/admin/dashboard', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminDashboardController())->index();
});

$router->get('/admin/pipeline', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminPipelineController())->index();
});

$router->get('/admin/pipeline/payments/pending', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminPipelineController())->pendingPayments();
});

$router->get('/admin/pipeline/payments/cancelled', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminPipelineController())->cancelledPayments();
});

$router->get('/admin/pipeline/payments/success', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminPipelineController())->successfulPayments();
});

$router->get('/admin/pipeline/consultations/active', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminPipelineController())->activeConsultations();
});

$router->get('/admin/pipeline/consultations/closed', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminPipelineController())->closedConsultations();
});

$router->patch('/admin/consultations/{id}/close', function (string $id): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new ConsultationController())->closeForAdmin($id);
});

$router->get('/admin/consultations/{id}/chat', function (string $id): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new ChatController())->showForAdmin($id);
});

$router->get('/api/admin/consultations/{id}/messages', function (string $id): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new ChatController())->messagesForAdmin($id);
});

$router->post('/api/admin/consultations/{id}/messages', function (string $id): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new ChatController())->sendByAdminApi($id);
});

$router->get('/admin/sub-services', function (): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminSubServiceController())->index();
});

$router->get('/admin/sub-services/{id}/edit', function (string $id): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle()) {
        return;
    }

    (new AdminSubServiceController())->edit($id);
});

$router->patch('/admin/sub-services/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !AdminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new AdminSubServiceController())->update($id);
});
