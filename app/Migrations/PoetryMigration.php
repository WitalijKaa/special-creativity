<?php

namespace App\Migrations;

use App\Models\Poetry\Poetry;
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
            $table->smallInteger('chapter')->nullable(false)->unsigned();
            $table->string('ai', 32)->nullable(true);
            $table->text('text')->nullable(false);
            $table->smallInteger('ix_text')->nullable(false)->unsigned();
            $table->integer('begin')->nullable(false)->unsigned(); // year
            $table->integer('end')->nullable(false)->unsigned(); // year
            $table->string('lang', 3)->nullable(false);
            $table->tinyInteger('part')->nullable(false)->unsigned();
            $table->tinyInteger('spectrum')->nullable(false)->unsigned()->default(Poetry::SPECTRUM_MAIN);

            // lp => life poetry (p == Person)
            $table->foreign('person_id', DB . '_lp_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('life_id', DB . '_lp_life')->references('id')->on(LifeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->index('lang', DB . '_lp_ai');
        });
    }
}
