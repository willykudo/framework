<?php

namespace WillyFramework\pkg;

use Throwable;
use WillyFramework\config\Env;

class ExceptionHandler {
    public static function handle(Throwable $exception): void {
        http_response_code(500);
        header('Content-Type: application/json');
        
        $debug = Env::get('APP_DEBUG');

        $response = [
            'error' => $debug ? $exception->getMessage() : "Internal Server Error"
        ];

        if($debug) {
            $response['file'] = $exception->getFile();
            $response['line'] = $exception->getLine();
            $response['trace'] = $exception->getTrace();
        }
        echo json_encode($response, JSON_PRETTY_PRINT);
    }
}

