<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PoetryWordMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_poetry_word'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('word', 125)->nullable(false);
            $table->string('word_eng', 125)->nullable(false);
            $table->text('definition')->nullable(false);
            $table->string('lang', 3)->nullable(false);

            $table->index('lang', DB . '_pw_lang');
        });
    }
}
