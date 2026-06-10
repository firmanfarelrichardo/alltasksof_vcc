<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Core\Response;
use App\Core\Session;

final class CsrfMiddleware
{
    public static function handle(): bool
    {
        require_once BASE_PATH . '/app/Helpers/view_helper.php';
        require_once BASE_PATH . '/app/Helpers/csrf_helper.php';

        $token = $_POST['_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        if (csrf_verify(is_string($token) ? $token : null)) {
            return true;
        }

        Session::flash('error', 'Token keamanan tidak valid. Silakan coba lagi.');
        Response::redirect('/login');
    }
}
