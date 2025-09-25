<?php 

namespace WillyFramework\src\Repository;

use WillyFramework\src\Models\User;
use WillyFramework\src\Core\BaseRepository;
use WillyFramework\src\Exception\NotFoundException;
use WillyFramework\src\Exception\DatabaseException;
use WillyFramework\src\Exception\ValidationException;
use PDO; 

class UserRepository extends BaseRepository{

    public function __construct(PDO $db){
        parent::__construct($db, 'users');
    }

    public function findAll(array $columns = ['*']): array {
        $rows = parent::findAll($columns);
        if ($rows === false) throw new DatabaseException("Failed to fetch users");
        return array_map(fn($r) => new User($r), $rows);
    }

    public function findUser(int $id, array $columns = ['*']): User {
        $row = parent::find($id, $columns);
        if (!$row) throw new NotFoundException("User with ID {$id} not found");
        return new User($row);
    }

    public function createUser(string $name, string $email, string $password, string $role, string $status): ?User {
      $id = parent::create([
        'name' => $name,
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'role' => $role,
        'status' => $status,
      ]);

      if (!$id) throw new DatabaseException("Failed to create user");

      return new User([
            'id' => $id, 
            'name' => $name, 
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'status' => $status,
        ]);
    }

    public function updateUser(int $id, array $data): ?User {
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }
        
        $succes = parent::update($id, $data);
        if (!$succes) throw new NotFoundException("User with {$id} not found or not updated");

        $row = parent::find($id);
        if (!$row) throw new DatabaseException("Failed to retrieve update user");

        return new User ($row);
    }

    public function deleteUser(int $id): bool {
        $deleted = parent::delete($id);
        if (!$deleted) throw new NotFoundException("User with {$id} not found");
        return true;
    }

    public function searchUsers(array $conditions, array $columns = ['*']): array {
        if (empty($conditions)) throw new ValidationException("At least one parameter is required");
        
        $rows = parent::where($conditions, $columns);
        if ($rows === false) throw new DatabaseException("Failed to search users");
        
        return array_map(fn($r)=> new User($r), $rows);
    }
}