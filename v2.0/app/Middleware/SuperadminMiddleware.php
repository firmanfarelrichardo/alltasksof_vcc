<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Core\Session;

final class SuperadminMiddleware
{
    public static function handle(): bool
    {
        if (Session::get('role') === 'superadmin' && Session::get('status') === 'approved') {
            return true;
        }

        Response::forbidden();
        return false;
    }
}
