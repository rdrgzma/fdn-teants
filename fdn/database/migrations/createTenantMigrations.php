<?php

namespace Fdn\database\migrations;

use Fdn\database\Database;
use Fdn\models\Tenant;

class  createTenantMigrations
{
    public function __construct(private Tenant $tenant)
    {
        $this->setTenant($this->tenant);
    }

    /**
     * @return Tenant
     */
    public function getTenant(): Tenant
    {
        return $this->tenant;
    }

    /**
     * @param Tenant $tenant
     */
    public function setTenant(Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function run()
    {
        $dBName = $this->getTenant()->getDbName();
        $query = "CREATE DATABASE IF NOT EXISTS $dBName";
        Database::connect()->query($query);
       Database::close();
        $this->createTable($dBName);
    }

    public function createTable($dBName): void
    {
     $queryCreateUserTable = "CREATE TABLE IF NOT EXISTS $dBName.users (
          id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(250) NOT NULL,
          username VARCHAR(250) NOT NULL,
          email VARCHAR(250),
          password VARCHAR(250),
          create_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
          )";
     Database::connect()->setTenantByName($this->getTenant()->getName())->query($queryCreateUserTable);
    }

    public function insertTableUsers($dBName,$data):void
    {
        $query = "INSERT INTO $dBName.users (name,username,email,password) VALUES (:name, :username, :email,:password)";
        Database::setTenantByName($dBName)->query($query,$data);
    }

}