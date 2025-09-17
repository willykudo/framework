<?php

namespace WillyFramework\src\Core;

class App {
    private array $config;

    public function __construct(){
        $this->loadEnv();
    }

    private function loadEnv():void {
        $envPath = __DIR__.'/../../.env';
        if (!file_exists($envPath)) {
            throw new \Exception(".env file not found");
        }

        $lines = file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (str_starts_with(trim($line), '#')) continue;
            [$key, $value] = explode('=', $line, 2);
            $this->config[trim($key)] = trim($value);
        }
    }

    public function getConfig():array {
        return $this->config;
    }
}
