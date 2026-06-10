<?php

declare(strict_types=1);

function url(string $uri): string
{
    $basePath = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');

    if ($basePath === '' || $basePath === '.') {
        return '/' . ltrim($uri, '/');
    }

    return $basePath . '/' . ltrim($uri, '/');
}
