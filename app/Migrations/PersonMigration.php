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
            $table->unsignedBigInteger('type_id')->nullable(true);
            $table->unsignedBigInteger('person_author_id')->nullable(true);
            $table->tinyInteger('force_person')->nullable(false)->default(0);
            $table->integer('begin')->nullable(false)->unsigned(); // year

            $table->foreign('type_id', DB . '_p_type')->references('id')->on(PersonTypeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('person_author_id', DB . '_p_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
