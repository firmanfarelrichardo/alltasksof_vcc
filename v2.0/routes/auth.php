<?php

declare(strict_types=1);

use App\Controllers\AuthController;
use App\Middleware\AuthMiddleware;
use App\Middleware\CsrfMiddleware;
use App\Middleware\GuestMiddleware;

$router->get('/register', function (): void {
    if (!GuestMiddleware::handle()) {
        return;
    }

    (new AuthController())->showRegister();
});

$router->post('/register', function (): void {
    if (!GuestMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new AuthController())->register();
});

$router->get('/login', function (): void {
    if (!GuestMiddleware::handle()) {
        return;
    }

    (new AuthController())->showLogin();
});

$router->post('/login', function (): void {
    if (!GuestMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new AuthController())->login();
});

$router->post('/logout', function (): void {
    if (!AuthMiddleware::handle() || !CsrfMiddleware::handle()) {
        return;
    }

    (new AuthController())->logout();
});
