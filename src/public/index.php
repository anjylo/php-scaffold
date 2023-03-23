<?php

declare(strict_types=1);

include __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$config = new App\Config($_ENV);
$container = new App\Core\Container;

$router = (new App\Core\Router($container))
        ->get('/', [App\Controllers\TestController::class, 'index']);

(new App\Core\App(
    ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
    $router, 
    $config
))
->run();