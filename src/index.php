<?php

$app = require __DIR__.'/../bootstrap.php';

use WillyFramework\src\Controllers\UserController;
use WillyFramework\src\Middleware\AuthMiddleware;

$container = require __DIR__.'/../config/Services.php';

$router = $app->getRouter();

$router->get('/users', function($req, $res) use ($container) {
    return $container->get(UserController::class)->index($req, $res);
}, [AuthMiddleware::class]);

$router->get('/users/{id}', function($req, $res, $id) use ($container) {
    return $container->get(UserController::class)->show($req, $res, (int)$id);
},[AuthMiddleware::class]);

$router->post('/users', function($req, $res) use ($container) {
    return $container->get(UserController::class)->store($req, $res);
}, [AuthMiddleware::class]);

$router->put('/users/{id}', function($req, $res, $id) use ($container) {
    return $container->get(UserController::class)->update($req, $res, (int)$id);
}, [AuthMiddleware::class]);

$router->delete('/users/{id}', function($req, $res, $id) use ($container) {
    return $container->get(UserController::class)->destroy($req, $res, (int)$id);
}, [AuthMiddleware::class]);

$router->get('/users/search', function($req, $res) use ($container) {
    return $container->get(UserController::class)->search($req, $res);
}, [AuthMiddleware::class]);


$app->run();
