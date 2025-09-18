<?php 

namespace WillyFramework\config;

use WillyFramework\pkg\ExceptionHandler;

class Env {
    public static function get(string $path): array {
        if(!file_exists($path)) {
            throw new \Exception("Env file not found");
        }

        $config = [];
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;

            [$key, $value] = explode('=', $line, 2);
            $config[trim($key)] = trim($value);

            $_ENV[$key] = $value;
        }
        return $config;
    }
}