<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Core\Session;
use App\Services\AuthService;

final class GuestMiddleware
{
    public static function handle(): bool
    {
        if (!Session::has('user_id')) {
            return true;
        }

        Response::redirect(AuthService::redirectPathForRole((string) Session::get('role')));
    }
}
