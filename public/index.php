<?php

declare(strict_types=1);

use App\Controllers\AdminController;
use App\Controllers\ArticleController;
use App\Controllers\AuthController;
use App\Controllers\CategoryController;
use App\Controllers\FrontController;
use App\Core\Database;
use App\Core\Request;
use App\Core\Router;

define('BASE_PATH', dirname(__DIR__));

spl_autoload_register(static function (string $class): void {
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = BASE_PATH . '/app/' . str_replace('\\', '/', $relative) . '.php';
    if (is_file($file)) {
        require $file;
    }
});

$appConfig = require BASE_PATH . '/config/config.php';
require BASE_PATH . '/app/helpers.php';

date_default_timezone_set((string) app_config('app.timezone', 'UTC'));

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

Database::init($appConfig['db']);

$router = new Router();

$router->get('/', [FrontController::class, 'home']);
$router->get('/articles', [FrontController::class, 'articles']);
$router->get('/article/{slug}', [FrontController::class, 'article']);
$router->get('/categorie/{slug}', [FrontController::class, 'category']);
$router->get('/robots.txt', [FrontController::class, 'robots']);
$router->get('/sitemap.xml', [FrontController::class, 'sitemap']);

$router->get('/admin/login', [AuthController::class, 'showLogin']);
$router->post('/admin/login', [AuthController::class, 'login']);
$router->post('/admin/logout', [AuthController::class, 'logout']);

$router->get('/admin', [AdminController::class, 'dashboard']);

$router->get('/admin/categories', [CategoryController::class, 'index']);
$router->get('/admin/categories/create', [CategoryController::class, 'create']);
$router->post('/admin/categories/create', [CategoryController::class, 'store']);
$router->get('/admin/categories/{id}/edit', [CategoryController::class, 'edit']);
$router->post('/admin/categories/{id}/edit', [CategoryController::class, 'update']);
$router->post('/admin/categories/{id}/delete', [CategoryController::class, 'delete']);

$router->get('/admin/articles', [ArticleController::class, 'index']);
$router->get('/admin/articles/create', [ArticleController::class, 'create']);
$router->post('/admin/articles/create', [ArticleController::class, 'store']);
$router->get('/admin/articles/{id}/edit', [ArticleController::class, 'edit']);
$router->post('/admin/articles/{id}/edit', [ArticleController::class, 'update']);
$router->post('/admin/articles/{id}/delete', [ArticleController::class, 'delete']);

$request = new Request();
$router->dispatch($request);
