<?php

namespace Fdn\models;

class Tenant
{
    private int $id;
    private String $name;
    private String $host;
    private String $port;
    private String $dbName;
    private String $userName;
    private String $password;

    /**
     * @param String $name
     * @param String $host
     * @param String $port
     * @param String $dbName
     * @param String $userName
     * @param String $password
     */
    public function __construct(string $name, string $host, string $port, string $dbName, string $userName, string $password)
    {
        $this->name = $name;
        $this->host = $host;
        $this->port = $port;
        $this->dbName = $dbName;
        $this->userName = $userName;
        $this->password = $password;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
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

    public function toArray(){

            return  array(
                "name"=>$this->getName(),
                "host"=>$this->getHost(),
                "port"=>$this->getPort(),
                "dbName"=>$this->getDbName(),
                "username"=>$this->getUserName(),
                "password"=>$this->getPassword()
            );

    }

}