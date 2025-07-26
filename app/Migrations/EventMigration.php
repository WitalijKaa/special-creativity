<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EventMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_event'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('type_id')->nullable(false);
            $table->integer('begin')->nullable(false)->unsigned(); // year
            $table->integer('end')->nullable(false)->unsigned(); // year
            $table->text('comment')->nullable(true);
            $table->unsignedBigInteger('work_id')->nullable(true);
            $table->tinyInteger('strong')->nullable(true)->unsigned();
            $table->unsignedBigInteger('life_id')->nullable(false);
            $table->unsignedBigInteger('person_id')->nullable(false);

            $table->foreign('type_id', DB . '_e_type')->references('id')->on(EventTypeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('life_id', DB . '_e_life')->references('id')->on(LifeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('person_id', DB . '_e_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('work_id', DB . '_ec_work')->references('id')->on(WorkMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->index('begin', DB . '_ec_begin');
        });
    }
}
