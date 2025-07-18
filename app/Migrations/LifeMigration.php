<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LifeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_life'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->integer('begin')->nullable(false); // year
            $table->integer('end')->nullable(false); // year
            $table->tinyInteger('role')->nullable(false);
            $table->unsignedBigInteger('person_id')->nullable(false);
            $table->unsignedBigInteger('type_id')->nullable(false);
            $table->unsignedBigInteger('planet_id')->nullable(true);

            $table->foreign('person_id', 'l_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id', 'l_type')->references('id')->on(LifeTypeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('planet_id', 'l_planet')->references('id')->on(PlanetMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
