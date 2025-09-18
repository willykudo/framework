<?php 

namespace WillyFramework\src\Core;

use PDO;
use PDOException;
use WillyFramework\pkg\ExceptionHandler;

class Database {
    private ?PDO $connection = null;

    public function __construct(array $config) {
        $host = $config['DB_HOST'];
        $port = $config['DB_PORT'];
        $dbname = $config['DB_DATABASE'];
        $user = $config['DB_USERNAME'];
        $pass = $config['DB_PASSWORD'];

        try {
            $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
            $this->connection = new PDO($dsn, $user, $pass,[
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]);
        } catch (PDOException) {
            throw new ExceptionHandler('Database connection failed');
        }
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
