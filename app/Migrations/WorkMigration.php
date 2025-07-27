<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WorkMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_work'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 128)->nullable(false)->unique();
            $table->integer('begin')->nullable(false)->unsigned(); // year
            $table->integer('end')->nullable(false)->unsigned(); // year
            $table->integer('capacity')->nullable(true)->unsigned();
            $table->smallInteger('consumers')->nullable(true)->unsigned();
        });
    }
}
