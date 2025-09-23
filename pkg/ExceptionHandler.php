<?php

namespace WillyFramework\pkg;

use Throwable;
use WillyFramework\config\Config;
use WillyFramework\src\Exception\AppException;
use WillyFramework\src\Exception\NotFoundException;
use WillyFramework\src\Exception\ValidationException;
use WillyFramework\src\Exception\DatabaseException;

class ExceptionHandler {

    public static function handle(Throwable $exception): void {
        header('Content-Type: application/json');
        $config = Config::get('APP_DEBUG');
        
        $status = 500;
        $message = "Internal Server Error";

        if ($exception instanceOf NotFoundException) {
            $status = 404;
            $message = $exception->getMessage();
        } elseif ($exception instanceOf ValidationException) {
            $status = 400;
            $message = $exception->getMessage();
        } elseif ($exception instanceOf DatabaseException) {
            $status = 500;
            $message = $exception->getMessage();
        } elseif ($exception instanceOf AppException) {
            $status = 500;
            $message = $exception->getMessage();
        } else {
            $message = $config ? $exception->getMessage() : $message;
        }

        http_response_code($status);
        
        $response = ['error' => $message];

        if($config) {
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = $exception->getTrace();
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

