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

    public function findAll(array $columns = ['id', 'name', 'email']): array {
        $rows = parent::findAll($columns);
        if ($rows === false) {
            throw new DatabaseException("Failed to fetch users");
        }
        return array_map(fn($r) => new User($r), $rows);
    }

    public function findUser(int $id, array $columns = ['id', 'name', 'email']): User {
        $row = parent::find($id, $columns);
        if (!$row) {
            throw new NotFoundException("User with ID {$id} not found");
        }
        return new User($row);
    }

    public function createUser(string $name, string $email): ?User {
      if(!$name || !$email) {
        throw new ValidationException("Name and Email cannot be emoty");
      }

      $id = parent::create([
        'name' => $name,
        'email' => $email
      ]);

      if (!$id){
        throw new DatabaseException("Failed to create user");
      }

      return new User(['id' => $id, 'name' => $name, 'email' => $email]);
    }

    public function updateUser(int $id, array $data): ?User {
        if (empty($data)) {
            throw new ValidationException("No data provided to update");
        }
        
        $succes = parent::update($id, $data);
        if (!$succes){
            throw new NotFoundException("User with {$id} not found or not updated");
        }

        $row = parent::find($id);
        if (!$row){
            throw new DatabaseException("Failed to retrieve update user");
        }

        return new User ($row);
    }

    public function deleteUser(int $id): bool {
        $deleted = parent::delete($id);
        if (!$deleted) {
            throw new NotFoundException("User with {$id} not found");
        }
        return true;
    }

    public function searchUsers(array $conditions, array $columns = ['id', 'name', 'email']): array {
        if (empty($conditions)) {
            throw new ValidationException("At least one parameter (name or email) is required");
        }
        
        $rows = parent::where($conditions, $columns);
        if ($rows === false) {
            throw new DatabaseException("Failed to search users");
        }
        
        return array_map(fn($r)=> new User($r), $rows);
    }
}