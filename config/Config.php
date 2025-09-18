<?php

namespace WillyFramework\config;

use WillyFramework\config\Env;

class Config {
    private static array $config = [];

    public static function load($envPath): void {
        self::$config = Env::get($envPath);
    }

    public static function get(string $key, $default = null) {
        return self::$config[$key] ?? $default;
    }

    public static function all(){
        return self::$config;
    }

}