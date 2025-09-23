<?php 

namespace WillyFramework\src\Repository;

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

    public function findUser(int $id, array $columns = ['id', 'name', 'email']): User {
        $row = parent::find($id, $columns);
        
        if (!$row) {
            throw new \Exception("User with ID {$id} not found");
        }
        return new User($row);
    }

    public function createUser(string $name, string $email): ?User {
      $id = parent::create([
        'name' => $name,
        'email' => $email
      ]);

      return new User(['id' => $id, 'name' => $name, 'email' => $email]);
    }

    public function updateUser(int $id, array $data): ?User {
        $succes = parent::update($id, $data);
        if (!$succes){
            return null;
        }
        $row = parent::find($id);
        return $row ? new User ($row) : null;
    }

    public function deleteUser(int $id): bool {
        return parent::delete($id);
    }

    public function searchUsers(array $conditions, array $columns = ['id', 'name', 'email']): array {
        $rows = parent::where($conditions, $columns);
        return array_map(fn($r)=> new User($r), $rows);
    }
}