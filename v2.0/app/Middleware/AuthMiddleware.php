<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Core\Session;

final class AuthMiddleware
{
    public static function handle(): bool
    {
        if (Session::has('user_id')) {
            return true;
        }

        Response::redirect('/login');
    }
}
