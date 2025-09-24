<?php

namespace WillyFramework\src\Models;

use WillyFramework\src\Enums\UserRole;
use WillyFramework\src\Enums\UserStatus;

class User {
    public int $id;
    public string $name;
    public string $email;
    public string $password; 
    public UserRole $role;     
    public UserStatus $status;

    public function __construct(array $data) {
        $this->id = $data['id'];
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
        $this->role = $data['role'] instanceof UserRole ? $data['role'] : UserRole::from($data['role']);
        $this->status = $data['status'] instanceof UserStatus ? $data['status'] : UserStatus::from($data['status']);
    }
}