<?php

namespace WillyFramework\src\Core;

use WillyFramework\config\Env;

class App {
    private array $config = [];

    public function __construct(string $envPath = __DIR__.'/../../.env'){
        $this->config = Env::get($envPath);
    }

    public function getConfig():array {
        return $this->config;
    }

    public function get(string $key, $default = null){
        return $this->config[$key] ?? $default;
    }
}
