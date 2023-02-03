<?php
namespace Fdn\database;

use Fdn\database\Database as Database;
use Fdn\database\migrations\createTenantMigrations;

class Migration
{
    private $pdo;

    public function __construct($db_info)
    {
        $this->pdo = Database::connect();
    }

    public function runMigrations($tenant_id)
    {
        $tenant= Database::connect()->query("SELECT * FROM tenants WHERE id = :id",["id"=>$tenant_id]);

        $files = scandir('migrations');
        foreach ($files as $file) {
            if (strpos($file, '.php')) {
                require_once 'migrations/' . $file;
                $migration = new createTenantMigrations($tenant);
                $migration->run();
                $migration_name = str_replace('.php', '', $file);
              //  $this->addMigrationLog($tenant_id, $migration_name);
            }
        }
    }

    private function addMigrationLog($tenant_id, $migration_name)
    {
        $stmt = $this->pdo->prepare("INSERT INTO migrations_log (tenant_id, migration_name) VALUES (?, ?)");

        $stmt->execute([$tenant_id, $migration_name]);
    }
}