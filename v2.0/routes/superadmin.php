<?php

declare(strict_types=1);

use App\Controllers\SuperadminDashboardController;
use App\Controllers\SuperadminAdminManagementController;
use App\Controllers\SuperadminServiceController;
use App\Controllers\SuperadminUserApprovalController;
use App\Controllers\ConsultationController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\SuperadminMiddleware;

$router->get('/superadmin/dashboard', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminDashboardController())->index();
});

$router->get('/superadmin/users/pending', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminUserApprovalController())->pending();
});

$router->patch('/superadmin/users/{id}/approve', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminUserApprovalController())->approve($id);
});

$router->patch('/superadmin/users/{id}/reject', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminUserApprovalController())->reject($id);
});

$router->get('/superadmin/services', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->index();
});

$router->get('/superadmin/services/create', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->create();
});

$router->post('/superadmin/services', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->store();
});

$router->get('/superadmin/services/{id}/edit', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->edit($id);
});

$router->patch('/superadmin/services/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->update($id);
});

$router->delete('/superadmin/services/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->destroy($id);
});

$router->get('/superadmin/sub-services', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->subServices();
});

$router->get('/superadmin/sub-services/create', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->createSubService();
});

$router->post('/superadmin/sub-services', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->storeSubService();
});

$router->get('/superadmin/sub-services/{id}/edit', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->editSubService($id);
});

$router->patch('/superadmin/sub-services/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->updateSubService($id);
});

$router->delete('/superadmin/sub-services/{id}', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminServiceController())->destroySubService($id);
});

$router->get('/superadmin/admins', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminAdminManagementController())->index();
});

$router->get('/superadmin/admins/{id}/assignments', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new SuperadminAdminManagementController())->assignments($id);
});

$router->post('/superadmin/admins/{id}/assignments', function (string $id): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminAdminManagementController())->storeAssignment($id);
});

$router->delete('/superadmin/admins/{id}/assignments/{assignmentId}', function (string $id, string $assignmentId): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new SuperadminAdminManagementController())->destroyAssignment($id, $assignmentId);
});

$router->get('/superadmin/consultations', function (): void {
    if (!AuthMiddleware::handle() || !SuperadminMiddleware::handle()) {
        return;
    }

    (new ConsultationController())->superadminIndex();
});
