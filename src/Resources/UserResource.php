<?php

namespace WillyFramework\src\Resources;

use WillyFramework\src\Core\Resource;

class UserResource extends Resource
{
    /**
     * Transform data user menjadi array
     * 
     * @return array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->data->id,
            'name' => $this->data->name,
            'email' => $this->data->email,
            'created_at' => $this->data->created_at,
            // Hilangkan data sensitif seperti password
        ];
    }
}