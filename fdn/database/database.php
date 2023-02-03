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
      $port = "3306";
      $dbName = "saas";
      $username = "root";
      $password = "root";

      self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
    } catch (PDOException $e) {
      die("A conexão falhou $dbName: " . $e->getMessage());
    }
    return new static();
  }

  public static function setTenant($tenantId = 1)
  {
    self::connect();
    $query = "SELECT * FROM tenants WHERE id = :tenantId";
    $stmt = self::$conn->prepare($query);
    $stmt->execute(["tenantId" => $tenantId]);
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $tenant = $stmt->fetch();
    if (!$tenant) {
      die("Tenant não encontrado set");
    }

    self::close();
    // Armazene as informações da conexão do tenant nas variáveis correspondentes
    $host = $tenant["host"];
    $port = $tenant["port"];
    $dbName = $tenant["dbName"];
    $username = $tenant["username"];
    $password = $tenant["password"];

    try {
      self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
    } catch (PDOException $e) {
      die("A conexão falhou $dbName : " . $e->getMessage());
    }
    return new static();
  }

    public static function setTenantByName($tenantName)
    {
        self::connect();
        $query = "SELECT * FROM tenants WHERE name = :tenantName";
        $stmt = self::$conn->prepare($query);
        $stmt->execute(["tenantName" => $tenantName]);
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $tenant = $stmt->fetch();
        if (!$tenant) {
            die("Tenant não $tenantName encontrado set");
        }

        self::close();
        // Armazene as informações da conexão do tenant nas variáveis correspondentes
        $host = $tenant["host"];
        $port = $tenant["port"];
        $dbName = $tenant["dbName"];
        $username = $tenant["username"];
        $password = $tenant["password"];

        try {
            self::$conn = new PDO("mysql:host=$host;port=$port;dbname=$dbName", $username, $password);
        } catch (PDOException $e) {
            die("A conexão falhou $dbName : " . $e->getMessage());
        }
        return new static();
    }

  public static function query($query, $params = [])
  {

    $stmt = self::$conn->prepare($query);
    if (!empty($params)) {
      // Sanitizar os parâmetros
      foreach ($params as $key => $value) {
        $stmt->bindParam($key, $value, PDO::PARAM_STR);
      }
      $stmt->execute($params);
    } else {
      $stmt->execute();
    }

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }

  public static function close()
  {
    self::$conn = null;
  }
}
