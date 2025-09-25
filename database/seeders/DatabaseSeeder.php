<?php 

namespace WillyFramework\database\seeders;

class DatabaseSeeder {
    public function run(): void {
        $this->call([
            UserSeeder::class,
        ]);
    }

    private function call(array $seeders): void {
        foreach ($seeders as $seederClass) {
            if (!class_exists($seederClass)) {
                echo "Seeder {$seederClass} not found";
                continue;
            }

            $seeder = new $seederClass();
            echo "Running {$seederClass}";
            $seeder->run();
        }
    }
}