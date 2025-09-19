<?php 

namespace WillyFramework\src\Repository;

use WillyFramework\src\Core\Database;
use WillyFramework\src\Models\User;
use WillyFramework\src\Core\BaseRepository;
use PDO;

class UserRepository extends BaseRepository{

    public function __construct(PDO $db){
        parent::__construct($db, 'users');
    }

    public function findAll(array $columns = ['id', 'name', 'email']): array {
        $rows = parent::findAll($columns);
        return array_map(fn($r) => new User($r), $rows);
    }

    public function createUser(string $name, string $email): User {
      $id = parent::create([
        'name' => $name,
        'email' => $email
      ]);

      return new User(['id' => $id, 'name' => $name, 'email' => $email]);
    }
}