<?php 

namespace WillyFramework\src\Core;

use PDO;
use PDOException;

class Database {
    private ?PDO $connection = null;

    public function __construct(array $config) {
        try {
            $dsn = "mysql:host={$config['DB_HOST']};port={$config['DB_PORT']};dbname={$config['DB_NAME']}";
            $this->connection = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'],[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed: '. $e->getMessage());
        }
    }

    public function getConnection(): ?PDO {
        return $this->connection;
    }
}
