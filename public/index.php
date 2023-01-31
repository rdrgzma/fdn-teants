<?php
session_start();
require "../vendor/autoload.php";

use Fdn\database\Database;

$res = Database::connect()->setTenant(2)->query("SELECT * FROM user WHERE id = :id", ["id" => 1]);
var_dump($res);
