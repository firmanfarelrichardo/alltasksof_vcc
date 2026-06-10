<?php

declare(strict_types=1);

use App\Core\Session;

function csrf_token(): string
{
    $token = Session::get('_csrf_token');

    if (is_string($token) && $token !== '') {
        return $token;
    }

    $token = bin2hex(random_bytes(32));
    Session::put('_csrf_token', $token);

    return $token;
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_verify(?string $token): bool
{
    $sessionToken = Session::get('_csrf_token');

    return is_string($sessionToken) && is_string($token) && hash_equals($sessionToken, $token);
}
