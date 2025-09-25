<?php

require __DIR__ . '/bootstrap.php';

use WillyFramework\database\seeders\DatabaseSeeder;

try {
    (new DatabaseSeeder())->run();
    echo "Seeding completed\n";
} catch (Throwable $e) {
    echo "Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}
