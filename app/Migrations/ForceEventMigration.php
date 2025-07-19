<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForceEventMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_event_force'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->integer('diff')->nullable(false);
            $table->integer('year')->nullable(true)->unsigned(); // year
            $table->unsignedBigInteger('person_id')->nullable(false);
            $table->unsignedBigInteger('life_id')->nullable(false);
            $table->unsignedBigInteger('type_id')->nullable(false);

            $table->foreign('person_id', DB . '_ef_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('life_id', DB . '_ef_life')->references('id')->on(LifeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('type_id', DB . '_ef_type')->references('id')->on(ForceEventTypeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
