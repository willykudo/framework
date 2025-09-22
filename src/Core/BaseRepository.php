<?php

namespace WillyFramework\src\Core;

use PDO;

class BaseRepository {
    protected PDO $db;
    protected string $table;

    public function __construct(PDO $db, string $table){
        $this->db = $db;
        $this->table = $table;
    }

    public function findAll(array $columns = ['*']): array {
        $cols = implode(',', $columns);
        $stmt = $this->db->query("SELECT {$cols} FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id, array $columns = ['*']): ?array {
        $cols = implode(',', $columns);
        $stmt = $this->db->prepare("SELECT {$cols} FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }

    public function create(array $data): int {
        $columns = array_keys($data);
        $colStr = implode(',', $columns);
        $placeholders = ':' . implode(',:', $columns);

        $stmt = $this->db->prepare("INSERT INTO {$this->table} ({$colStr}) VALUES ({$placeholders})");
        $stmt->execute($data);

        return (int) $this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool {
        $set = implode(',', array_map(fn($key) => "{$key} = :{$key}", array_keys($data)));
        $data['id'] = $id;
        $stmt = $this->db->prepare("UPDATE {$this->table} SET {$set} WHERE id = :id");
        return $stmt->execute($data);
    }

    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function where(array $conditions, array $columns = ['*']): array {
        $cols = implode(',', $columns);
        $where = implode(' AND ', array_map(fn($key) => "{$key} LIKE :{$key}", array_keys($conditions)));

        $stmt = $this->db->prepare("SELECT {$cols} FROM {$this->table} WHERE {$where}");
        foreach ($conditions as $key => $value){
            $stmt->bindValue(":$key", "%$value%");
        }
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
