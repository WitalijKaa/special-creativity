<?php

namespace App\Migrations;

use Illuminate\Support\Facades\Schema;

class Migrator implements MigratorInterface
{
    public static function tableName(): string { return DB . '_migrations'; }

    private const array MIGRATIONS = [
        PlanetMigration::class,
        PersonTypeMigration::class,
        ParentsTypeMigration::class,
        PersonMigration::class,
        LifeTypeMigration::class,
        LifeMigration::class,
    ];

    public static function migrate(): void
    {
        foreach (self::MIGRATIONS as $migration) {
            if (!Schema::hasTable($migration::tableName())) {
                $migration::migrate();
            }
        }
    }

    public static function drop(): void
    {
        foreach (array_reverse(self::MIGRATIONS) as $migration) {
            Schema::dropIfExists($migration::tableName());
        }
    }
}
