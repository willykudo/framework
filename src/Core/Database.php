<?php 

namespace WillyFramework\src\Core;

use PDO;
use PDOException;
use WillyFramework\config\Config;

class Database {
    private ?PDO $connection = null;

    public function __construct() {
        $host = Config::get('DB_HOST');
        $port = Config::get('DB_PORT');
        $dbname = Config::get('DB_DATABASE');
        $user = Config::get('DB_USERNAME');
        $pass = Config::get('DB_PASSWORD');

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $user, $pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException $e) {
            throw new \Exception('Database connection failed : ' . $e->getMessage());
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
