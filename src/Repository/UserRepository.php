<?php 

namespace WillyFramework\src\Repository;

use WillyFramework\src\Core\Database;
use WillyFramework\src\Models\User;
use PDO;

class UserRepository {
    private PDO $db;

    public function __construct(PDO $db){
        $this->db = $db;
    }

    public function findAll(): array {
        $stmt = $this->db->query("SELECT id, name, email FROM users");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return array_map(fn($r) => new User($r), $rows);
    }

    public function create(string $name, string $email): ?User {
       try {
            $stmt = $this->db->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
            $stmt->execute([
                'name' => $name, 
                'email' => $email
            ]);

            $id = $this->db->lastInsertId();
            return new User(['id' => $id, 'name' => $name, 'email' => $email]);
       }catch (PDOException $e){
        throw new \Exception("Data failed to create");
       }
    }
}