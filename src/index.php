<?php

require __DIR__.'/../vendor/autoload.php';

use WillyFramework\src\Core\App;
use WillyFramework\src\Controllers\UserController;

$app = new App();

$db = $app->getDb()->getConnection();
$router = $app->getRouter();

$router->get('/users', [UserController::class, 'index']);
$router->post('/users', [UserController::class, 'store']);

echo json_encode([
    "status" => "success",
    "message" => "Database connected successfully"
]);

$app->run();
