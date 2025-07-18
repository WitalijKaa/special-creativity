<?php

namespace App\Migrations;

use Illuminate\Support\Facades\Schema;

class Migrator implements MigratorInterface
{
    public static function tableName(): string { return DB . '_migrations'; }

    private const array MIGRATIONS = [
        PlanetMigration::class,
    ];

    public static function migrate(): void
    {
        foreach (self::MIGRATIONS as $migration) {
            if (!Schema::hasTable($migration::tableName())) {
                $migration::migrate();
            }
        }
    }
}
