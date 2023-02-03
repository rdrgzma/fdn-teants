<?php
session_start();
require "../vendor/autoload.php";

use Fdn\App\UserCase\CreateTenant;
use Fdn\database\Database;
use Fdn\models\Model;

//$res = Database::connect()->setTenant(2)->query("SELECT * FROM user WHERE id = :id", ["id" => 1]);
//$_SESSION["tenantId"] = 3;
class User extends Model{
        protected $table = "user";
}

//$user = new User();
//$users = $user->all();
//$user_1 = $user->find(1);

$createTenant = new CreateTenant('tenant_9');
$createTenant->load();


