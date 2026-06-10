<?php

declare(strict_types=1);

namespace App\Core;

final class Response
{
    public static function redirect(string $uri): void
    {
        header('Location: ' . self::toUrl($uri), true, 302);
        exit;
    }

    public static function json(array $payload, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($payload, JSON_THROW_ON_ERROR);
    }

    public static function notFound(): void
    {
        http_response_code(404);
        echo '404 - Halaman tidak ditemukan.';
    }

    public static function forbidden(): void
    {
        http_response_code(403);
        echo 'Anda tidak memiliki akses ke halaman ini.';
    }

    private static function toUrl(string $uri): string
    {
        $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

        if ($basePath === '' || $basePath === '.') {
            return $uri;
        }

        return $basePath . '/' . ltrim($uri, '/');
    }
}
