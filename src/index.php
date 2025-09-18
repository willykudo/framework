<?php

require __DIR__.'/../vendor/autoload.php';

use WillyFramework\src\Core\App;
use WillyFramework\src\Core\Database;
use WillyFramework\pkg\ExceptionHandler;

set_exception_handler([ExceptionHandler::class, 'handle']);

$app = new App();

$db = new Database();
$connection = $db->getConnection();

echo json_encode([
    "status" => "success",
    "message" => "Database connected successfully"
]);

