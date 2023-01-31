<?php

namespace Fdn\database;

use PDO;

class Database
{
    private static $conn;
  
    public static function connect()
    {
      try {
        $host = "localhost";
        $port = "3307";
        $dbName = "saas";
        $username = "root";
        $password = "";
  
        self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
        
      } catch (PDOException $e) {
        die("A conexão falhou: " . $e->getMessage());
      }
      return new static;
    }
  
    public static function setTenant($tenantId)
    {
      self::connect();
  
      $query = self::$conn->prepare("SELECT * FROM tenants WHERE tenant_id = :tenantId OR name = :tenantId");
      $query->execute(["tenantId" => $tenantId]);
      $tenant = $query->fetch(PDO::FETCH_ASSOC);
            if (!$tenant) {
        die("Tenant não encontrado");
      }
  
      // Armazene as informações da conexão do tenant nas variáveis correspondentes
      $host = $tenant["host"];
      $port = $tenant["port"];
      $dbName = $tenant["db_name"];
      $username = $tenant["username"];
      $password = $tenant["password"];
  
      try {
        self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
    

      } catch (PDOException $e) {
        die("A conexão falhou: " . $e->getMessage());
      }
    }
    public static function query($query, $params = []) {
        // Sanitizar a consulta
        $query = filter_var($query, FILTER_SANITIZE_STRING);
        if(!empty($params )){
            // Sanitizar os parâmetros
        foreach ($params as $key => $value) {
            $params[$key] = filter_var($value, FILTER_SANITIZE_STRING);
          }
      
          $stmt = self::$conn->prepare($query);
          $stmt->execute($params);
        }else{
            $stmt = self::$conn->prepare($query);
            $stmt->execute();
        }
    
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
      }
    
      public static function selectJoin($table1, $table2, $joinOn, $select = '*')
      {
        // Sanitizar as tabelas e a cláusula ON
        $table1 = filter_var($table1, FILTER_SANITIZE_STRING);
        $table2 = filter_var($table2, FILTER_SANITIZE_STRING);
        $joinOn = filter_var($joinOn, FILTER_SANITIZE_STRING);
    
        $query = "SELECT $select FROM $table1 INNER JOIN $table2 ON $table1.$joinOn = $table2.$joinOn";
        return self::query($query);
      }
    
      public static function close()
      {
        self::$conn = null;
      }

  }
  