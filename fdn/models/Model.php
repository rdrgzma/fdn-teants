<?php
namespace Fdn\models;
use Fdn\database\Database;
use \PDO;


abstract class Model {
    protected $conn;
    protected $table;
    protected $tenantId = 1;

    public function __construct() {
        if (isset($_SESSION['tenantId'])){
            $this->tenantId = $_SESSION['tenantId'];
        }
        $this->conn = Database::connect()->setTenant($this->tenantId); 
    }

    public function all() {
        return $this->conn->query("SELECT * FROM {$this->table}");
    }

    public function find($id) {
        return $this->conn->query("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1", ["id" => $id]);  
    }

    public function where($column, $value) {
        return $this->conn->query("SELECT * FROM {$this->table} WHERE $column = :value LIMIT 1", ["value" => $value]);
        
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
        return $this->conn->query("INSERT INTO {$this->table} ($columns) VALUES ($values)");
        
    }

    private function update() {
        $updates = '';
        foreach (get_object_vars($this) as $key => $value) {
            $updates .= "$key = :$key, ";
        }
        $updates = rtrim($updates, ', ');
        return $this->conn->query("UPDATE {$this->table} SET $updates WHERE id = :id");
    }
}
