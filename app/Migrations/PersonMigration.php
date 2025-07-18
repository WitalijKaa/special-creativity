<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersonMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_person'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->nullable(false);
        });
    }
}
