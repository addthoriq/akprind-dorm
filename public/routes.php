<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Kang\AkprindDorm\App\Router;
use Kang\AkprindDorm\Controller\HomeController;
use Kang\AkprindDorm\Controller\ProductController;
use Kang\AkprindDorm\Middleware\AuthMiddleware;

Router::add('GET', '/products/([0-9a-zA-Z]*)/categories/([0-9a-zA-Z]*)', ProductController::class, 'categories');

Router::add('GET', '/', HomeController::class, 'index');
Router::add('GET', '/hello', HomeController::class, 'hello', [AuthMiddleware::class]);
Router::add('GET', '/world', HomeController::class, 'world', [AuthMiddleware::class]);
Router::add('GET', '/about', HomeController::class, 'about');

Router::run();
