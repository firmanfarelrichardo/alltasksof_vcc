<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'name' => Env::get('APP_NAME', 'Zeta'),
    'env' => Env::get('APP_ENV', 'development'),
    'debug' => Env::get('APP_DEBUG', 'false') === 'true',
    'base_url' => Env::get('APP_URL', 'http://localhost/alltasksof_vcc/v2.0/public'),
];
