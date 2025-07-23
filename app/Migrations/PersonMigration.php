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
            $table->string('name', 128)->nullable(false)->unique();
            $table->string('nick', 32)->nullable(false);
            $table->tinyInteger('type_id')->nullable(false)->unsigned(); // kill _id
            $table->unsignedBigInteger('person_author_id')->nullable(true);
            $table->tinyInteger('force_person')->nullable(false)->default(0);
            $table->integer('begin')->nullable(false)->unsigned(); // year

            $table->foreign('person_author_id', DB . '_p_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
