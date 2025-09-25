<?php

$app = require __DIR__ . '/bootstrap.php';
$db = $app->getDb();

$migrationFiles = glob(__DIR__ . '/database/migrations/*.php');

foreach ($migrationFiles as $file) {
    echo "Running migration: " . basename($file) . PHP_EOL;

    $migration = require $file;   
    $migration->up($db);          

    echo "Done: " . basename($file) . PHP_EOL;
}
