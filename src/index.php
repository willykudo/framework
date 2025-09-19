<?php

require __DIR__.'/../vendor/autoload.php';

use WillyFramework\src\Core\App;
use WillyFramework\src\Controllers\UserController;

$container = require __DIR__.'/../config/Services.php';

$app = new App();

$router = $app->getRouter();

$router->get('/users', function($req, $res) use ($container) {
    return $container->get(UserController::class)->index($req, $res);
});

$router->get('/users/{id}', function($req, $res, $id) use ($container) {
    return $container->get(UserController::class)->show($req, $res, (int)$id);
});

$router->post('/users', function($req, $res) use ($container) {
    return $container->get(UserController::class)->store($req, $res);
});

$router->put('/users/{id}', function($req, $res, $id) use ($container) {
    return $container->get(UserController::class)->update($req, $res, (int)$id);
});

$router->delete('/users/{id}', function($req, $res, $id) use ($container) {
    return $container->get(UserController::class)->destroy($req, $res, (int)$id);
});

$router->get('/users/search', function($req, $res) use ($container) {
    return $container->get(UserController::class)->search($req, $res);
});


$app->run();
