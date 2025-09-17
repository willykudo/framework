<?php

namespace WillyFramework\pkg;

class ExceptionHandler {
    public static function handle($exception) {
        http_response_code(500);
        header('Content-Type: application/json');

        echo json_encode([
            "error" => $exception->getMessage()
        ]);
    }
}

