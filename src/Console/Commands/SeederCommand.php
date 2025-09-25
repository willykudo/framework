<?php 

namespace WillyFramework\src\Console\Commands;

class SeederCommand {
    public string $description = "Run all database seeders";

    public function execute(): void {
        require __DIR__ . '/../../../seed.php';
    }
}