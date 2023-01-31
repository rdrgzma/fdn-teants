<?php
session_start();
require "../vendor/autoload.php";
use Fdn\database\Database;

$host = "localhost";
        $port = "3307";
        $dbName = "saas";
        $username = "root";
        $password = "";
$results = new PDO("mysql:host=localhost;port=3307;dbname=saas", "root","");
$query = "SELECT * FROM tenants";
$results->query($query);
$results->fetch();
//$results->execute();

var_dump($result);
