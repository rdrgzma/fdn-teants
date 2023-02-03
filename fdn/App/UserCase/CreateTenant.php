<?php

namespace Fdn\App\UserCase;

use Fdn\database\Database;
use Fdn\database\migrations\createTenantMigrations;
use Fdn\models\Tenant;
use Fdn\models\User;

class CreateTenant
{
    private Tenant $tenant;
    private String $name;
    private String $host = "localhost";
    private String $port = "3306";
    private String $dbName;
    private String $userName = "root";
    private String $password = "root";
    public function __construct(String $name)
    {
        if( Database::connect()->query("SELECT * FROM tenants WHERE name = :name",["name"=>$name])){
            die("Tenant $name já está em uso!");
        }
        $this->setName($name);
        $this->setDbName($name);
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
    public function setTenant($name, $host, $port, $dbName, $userName, $password): void
    {
        $this->tenant = new Tenant($name,$host,$port,$dbName,$userName,$password);
    }

    /**
     * @return String
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param String $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return String
     */
    public function getHost(): string
    {
        return $this->host;
    }

    /**
     * @param String $host
     */
    public function setHost(string $host): void
    {
        $this->host = $host;
    }

    /**
     * @return String
     */
    public function getPort(): string
    {
        return $this->port;
    }

    /**
     * @param String $port
     */
    public function setPort(string $port): void
    {
        $this->port = $port;
    }

    /**
     * @return String
     */
    public function getDbName(): string
    {
        return $this->dbName;
    }

    /**
     * @param String $dbName
     */
    public function setDbName(string $dbName): void
    {
        $this->dbName = $dbName;
    }

    /**
     * @return String
     */
    public function getUserName(): string
    {
        return $this->userName;
    }

    /**
     * @param String $userName
     */
    public function setUserName(string $userName): void
    {
        $this->userName = $userName;
    }

    /**
     * @return String
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param String $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function load()
        {
            $this->setTenant($this->getName(),$this->getHost(),$this->getPort(),$this->getDbName(),$this->getUserName(),$this->getPassword());
            $this->registerTenantDB($this->getTenant());
            $this->createTenantMigrate();
        }
        public function createTenantMigrate()
        {
         $migration = New createTenantMigrations($this->getTenant());
         $migration->run();
        }

        public function registerTenantDB(Tenant $tenant)
        {
            $query = "INSERT INTO tenants (name, host, port, dbName, username, password) VALUES (:name, :host, :port, :dbName, :username, :password)";
            return Database::connect()->query($query,$tenant->toArray());
        }

        public function insertUser(User $user)
        {
            $migration = New createTenantMigrations($this->getTenant());
            $migration->insertTableUsers($this->dbName,$user->toArray());
        }

}