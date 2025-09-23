<?php

$app = require __DIR__.'/../bootstrap.php';

use WillyFramework\src\Controllers\UserController;
use WillyFramework\src\Middleware\AuthMiddleware;

$router = $app->getRouter();

// make route clean
$router->resource('/users', UserController::class, [AuthMiddleware::class]);

$app->run();
