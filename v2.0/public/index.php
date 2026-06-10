<?php

declare(strict_types=1);

use App\Core\App;
use App\Core\Env;
use App\Core\Response;
use App\Core\Router;

define('BASE_PATH', dirname(__DIR__));

$composerAutoload = BASE_PATH . '/vendor/autoload.php';
if (is_file($composerAutoload)) {
    require $composerAutoload;
}

spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (is_file($file)) {
        require $file;
    }
});

Env::load(BASE_PATH . '/.env');

require BASE_PATH . '/app/Helpers/view_helper.php';
require BASE_PATH . '/app/Helpers/url_helper.php';
require BASE_PATH . '/app/Helpers/csrf_helper.php';

$appConfig = require BASE_PATH . '/config/app.php';

error_reporting(E_ALL);
ini_set('display_errors', $appConfig['debug'] ? '1' : '0');

set_exception_handler(function (Throwable $exception) use ($appConfig): void {
    http_response_code(500);

    if ($appConfig['debug']) {
        echo '<pre>' . htmlspecialchars((string) $exception, ENT_QUOTES, 'UTF-8') . '</pre>';
        return;
    }

    error_log($exception->getMessage());
    echo 'Terjadi kesalahan pada sistem.';
});

$router = new Router();

require BASE_PATH . '/routes/web.php';
require BASE_PATH . '/routes/auth.php';
require BASE_PATH . '/routes/user.php';
require BASE_PATH . '/routes/admin.php';
require BASE_PATH . '/routes/superadmin.php';
require BASE_PATH . '/routes/payment.php';

(new App($router))->run(
    $_SERVER['REQUEST_METHOD'] ?? 'GET',
    $_SERVER['REQUEST_URI'] ?? '/'
);
