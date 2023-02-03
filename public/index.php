<?php
session_start();
require "../vendor/autoload.php";

use Fdn\App\UserCase\CreateTenant;
use Fdn\database\Database;
use Fdn\database\migrations\createTenantMigrations;
use Fdn\models\Model;
use Fdn\models\User;


//$res = Database::connect()->setTenant(2)->query("SELECT * FROM user WHERE id = :id", ["id" => 1]);
//$_SESSION["tenantId"] = 3;



$user = new User("Usuário 1","user_1", "user_1@email.com", "123");
//$users = $user->all();
//$user_1 = $user->find(1);

$createTenant = new CreateTenant('tenant_9');
$createTenant->load();
$createTenant->insertUser($user);


