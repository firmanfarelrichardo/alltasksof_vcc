<?php

declare(strict_types=1);

use App\Core\Env;

return [
    'provider' => Env::get('PAYMENT_PROVIDER', 'midtrans'),
    'mode' => Env::get('PAYMENT_MODE', 'sandbox'),
    'midtrans' => [
        'server_key' => Env::get('MIDTRANS_SERVER_KEY', ''),
        'client_key' => Env::get('MIDTRANS_CLIENT_KEY', ''),
        'is_production' => Env::get('MIDTRANS_IS_PRODUCTION', 'false') === 'true',
        'notification_url' => Env::get('MIDTRANS_NOTIFICATION_URL', ''),
        'finish_redirect_url' => Env::get('MIDTRANS_FINISH_REDIRECT_URL', ''),
        'unfinish_redirect_url' => Env::get('MIDTRANS_UNFINISH_REDIRECT_URL', ''),
        'error_redirect_url' => Env::get('MIDTRANS_ERROR_REDIRECT_URL', ''),
    ],
];
