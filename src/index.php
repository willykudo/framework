<?php

require __DIR__.'/../vendor/autoload.php';

use WillyFramework\src\Core\App;
use WillyFramework\src\Core\Database;
use WillyFramework\pkg\ExceptionHandler;

try{
    $app = new App();

    $config = $app->getConfig();

    $db = new Database($config);

    echo json_encode([
        "message" => "Database connected succesfully"
    ]);

}catch(\Throwable $e){
    ExceptionHandler::handle($e);
}
