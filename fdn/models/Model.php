<?php
namespace Fdn\models;
use Fdn\database\Database;


abstract class Model {
    protected $db;
    protected $table;

    public function __construct() {
        $this->db = new Database();
    }

    public function all() {
        $stmt = $this->db->pdo->prepare("SELECT * FROM {$this->table}");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function find($id) {
        $stmt = $this->db->pdo->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetchObject(get_class($this));
    }

    public function where($column, $value) {
        $stmt = $this->db->pdo->prepare("SELECT * FROM {$this->table} WHERE $column = :value LIMIT 1");
        $stmt->bindValue(':value', $value);
        $stmt->execute();
        return $stmt->fetchObject(get_class($this));
    }

    public function save() {
        if (isset($this->id)) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    private function insert() {
        $columns = implode(', ', array_keys(get_object_vars($this)));
        $values = ':' . implode(', :', array_keys(get_object_vars($this)));
        $stmt = $this->db->pdo->prepare("INSERT INTO {$this->table} ($columns) VALUES ($values)");
        return $stmt->execute(get_object_vars($this));
    }

    private function update() {
        $updates = '';
        foreach (get_object_vars($this) as $key => $value) {
            $updates .= "$key = :$key, ";
        }
        $updates = rtrim($updates, ', ');
        $stmt = $this->db->pdo->prepare("UPDATE {$this->table} SET $updates WHERE id = :id");
        return $stmt->execute(get_object_vars($this));
    }
}
