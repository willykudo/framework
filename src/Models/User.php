<?php

namespace WillyFramework\src\Models;

use WillyFramework\src\Core\Database;

class User {
    public int $id;
    public string $name;
    public string $email;

    public function __construct(array $data) {
        $this->id = $data['id'] ?? 0 ;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
    }
}