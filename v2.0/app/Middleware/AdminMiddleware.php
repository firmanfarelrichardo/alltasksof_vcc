<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Core\Session;

final class AdminMiddleware
{
    public static function handle(): bool
    {
        if (Session::get('role') === 'admin' && Session::get('status') === 'approved') {
            return true;
        }

        Response::forbidden();
        return false;
    }
}
