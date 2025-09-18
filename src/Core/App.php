<?php

namespace WillyFramework\src\Core;

use WillyFramework\config\Config;

class App {
    public function __construct(string $envPath = __DIR__.'/../../.env') {
        Config::load($envPath);
    }

    public function run(): void {

    }
}
