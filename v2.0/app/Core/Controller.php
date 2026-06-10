<?php

declare(strict_types=1);

namespace App\Core;

abstract class Controller
{
    protected function view(string $view, array $data = []): void
    {
        extract($data, EXTR_SKIP);

        require BASE_PATH . '/app/Views/' . str_replace('.', '/', $view) . '.php';
    }
}
