<?php 

namespace WillyFramework\src\Console\Commands;

class MigrateCommand {
    public string $description = "Run all database migrations";

    public function execute(): void {
        require __DIR__ . '/../../../migrate.php';
    }
}