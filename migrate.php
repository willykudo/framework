<?php

require_once __DIR__ . '/vendor/autoload.php';

use WillyFramework\src\Core\Database;
use WillyFramework\config\Config;

Config::load(__DIR__ . '/.env'); 

$db = Database::getInstance()->getConnection();

$migrationFiles = glob(__DIR__ . '/database/migrations/*.php');

foreach ($migrationFiles as $file) {
    echo "Running migration: " . basename($file) . PHP_EOL;

    $migration = require $file;   
    $migration->up($db);          

    echo "Done: " . basename($file) . PHP_EOL;
}
