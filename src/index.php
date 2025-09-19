<?php

require __DIR__.'/../vendor/autoload.php';

use WillyFramework\src\Core\App;
use WillyFramework\src\Controllers\UserController;

$app = new App();

$router = $app->getRouter();

$router->get('/users', function($req, $res) use ($container) {
    $container->get(UserController::class)->index($req, $res);
})

$router->post('/users', function($req, $res) use ($container) {
    $container->get(UserController::class)->store($req, $res);
})

echo json_encode([
    "status" => "success",
    "message" => "Database connected successfully"
]);

$app->run();
