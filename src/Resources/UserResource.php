<?php 

namespace WillyFramework\src\Resources;

use WillyFramework\src\Models\User;

class UserResource {
    private array $data = [];

    public function __construct(User|array $user) {
        if (is_array($user)) {
            $this->data = array_map(fn($u) => $this->transform($u), $user);
        } else {
            $this->data = $this->transform($user);
        }
    }

    private function transform(User $user): array {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
        ];
    }

    public function toArray(): array {
        return $this->data;
    }
}
