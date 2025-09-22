<?php 

namespace WillyFramework\database\seeders;

use WillyFramework\src\Core\Database;
use Faker\Factory as Faker;

class UserSeeder {
    public function run(int $count = 50, int $batchSize = 20): void {
        $db = Database::getInstance()->getConnection();
        $faker = Faker::create();

        $db->beginTransaction();

        try {
            $batches = ceil($count / $batchSize);

            for ($b = 0; $b < $batches; $b++) {
                $placeholders = [];
                $values = [];

                $rowsThisBatch = min($batchSize, $count - ($b * $batchSize));

                for ($i = 0; $i < $rowsThisBatch; $i++) {
                    $placeholders[] = "(?, ?)";
                    $values[] = $faker->name();
                    $values[] = $faker->unique()->safeEmail();
                }

                $sql = "INSERT INTO users (name, email) VALUES " . implode(",", $placeholders);
                $stmt = $db->prepare($sql);
                $stmt->execute($values);
            }

            $db->commit();
        } catch (\Throwable $e) {
            $db->rollBack();
            throw $e;
        }
    }
}