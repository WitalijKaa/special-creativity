<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PlanetMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_planet'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 32)->nullable(false);
            $table->unsignedSmallInteger('days')->nullable(false);
            $table->unsignedSmallInteger('hours')->nullable(false);
            $table->unsignedTinyInteger('force')->nullable(false);
            $table->unsignedTinyInteger('force_create')->nullable(false);
            $table->unsignedTinyInteger('force_man_up')->nullable(false);
            $table->unsignedTinyInteger('force_woman_up')->nullable(false);
            $table->unsignedTinyInteger('force_woman_special_up')->nullable(false);
            $table->unsignedTinyInteger('force_woman_man_allowed')->nullable(false);
            $table->unsignedTinyInteger('force_man_first_up')->nullable(false);
            $table->unsignedTinyInteger('force_woman_first_up')->nullable(false);
            $table->unsignedSmallInteger('force_man_min')->nullable(false);
            $table->unsignedSmallInteger('force_woman_min')->nullable(false);
        });
    }
}
