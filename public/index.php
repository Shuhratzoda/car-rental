<?php

declare(strict_types=1);

session_start();

define('BASE_PATH', dirname(__DIR__));

require BASE_PATH . '/src/Core/helpers.php';

// Autoload оддӣ
spl_autoload_register(function (string $class): void {
    $prefix = 'App\\';
    if (strncmp($prefix, $class, strlen($prefix)) !== 0) {
        return;
    }
    $relative = str_replace('\\', '/', substr($class, strlen($prefix)));
    $file = BASE_PATH . '/src/' . $relative . '.php';
    if (is_file($file)) {
        require $file;
    }
});

use App\Core\Database;
use App\Core\Router;
use App\Core\View;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\CarController;
use App\Controllers\BookingController;
use App\Controllers\ManagerController;
use App\Controllers\ReportController;

// Конфигуратсия
$configFile = BASE_PATH . '/config/config.php';
if (!is_file($configFile)) {
    $configFile = BASE_PATH . '/config/config.example.php';
}
$config = require $configFile;

ini_set('display_errors', $config['debug'] ? '1' : '0');
error_reporting(E_ALL);

Database::configure($config['db']);

// Коркарди истисноҳои дастгирнашуда
set_exception_handler(function (Throwable $e) use ($config): void {
    error_log((string) $e);
    http_response_code(500);
    echo (new View(BASE_PATH . '/views'))->render('errors/error', [
        'title'   => 'Хатои дохилии сервер',
        'code'    => 500,
        'message' => $config['debug'] ? $e->getMessage() : 'Баъдтар кӯшиш кунед ё ба администратор муроҷиат намоед.',
    ]);
});

// Масирҳо
$router = new Router();

// Асосӣ
$router->get('/', [HomeController::class, 'index']);

// Auth
$router->get('/login', [AuthController::class, 'loginForm']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/register', [AuthController::class, 'registerForm']);
$router->post('/register', [AuthController::class, 'register']);
$router->get('/logout', [AuthController::class, 'logout']);

// Мошинҳо (CRUD)
$router->get('/cars', [CarController::class, 'index']);
$router->get('/cars/create', [CarController::class, 'create']);
$router->post('/cars/create', [CarController::class, 'store']);
$router->get('/cars/{id}', [CarController::class, 'show']);
$router->get('/cars/{id}/edit', [CarController::class, 'edit']);
$router->post('/cars/{id}/edit', [CarController::class, 'update']);
$router->post('/cars/{id}/delete', [CarController::class, 'delete']);

// Бандкунӣ
$router->get('/book/{carId}', [BookingController::class, 'createForm']);
$router->post('/book/{carId}', [BookingController::class, 'store']);
$router->get('/my', [BookingController::class, 'my']);
$router->post('/my/cancel', [BookingController::class, 'cancel']);

// Менеҷер
$router->get('/manager', [ManagerController::class, 'index']);
$router->post('/manager/action', [ManagerController::class, 'action']);

// Ҳисобот
$router->get('/reports/income', [ReportController::class, 'income']);

// Иҷро
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI'] ?? '/');
