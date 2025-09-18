<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PoetryMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_poetry'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id')->nullable(false);
            $table->unsignedBigInteger('life_id')->nullable(false);
            $table->integer('begin')->nullable(false)->unsigned(); // year
            $table->integer('end')->nullable(false)->unsigned(); // year
            $table->smallInteger('ix_text')->nullable(false)->unsigned();
            $table->text('text')->nullable(false);
            $table->string('lang', 3)->nullable(false);

            // lp => life poetry (p == Person)
            $table->foreign('person_id', DB . '_lp_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('life_id', DB . '_lp_life')->references('id')->on(LifeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->index('begin', DB . '_lp_begin');
            $table->index('end', DB . '_lp_end');
            $table->index('lang', DB . '_lp_lang');
        });
    }
}
