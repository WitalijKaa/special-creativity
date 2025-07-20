<?php

namespace App\Migrations;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EventConnectMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_event_connect'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id')->nullable(false);
            $table->unsignedBigInteger('person_id')->nullable(false);
            $table->unsignedBigInteger('life_id')->nullable(false);

            $table->foreign('event_id', DB . '_ec_event')->references('id')->on(EventMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('person_id', DB . '_ec_person')->references('id')->on(PersonMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('life_id', DB . '_ec_life')->references('id')->on(LifeMigration::tableName())->onDelete('cascade')->onUpdate('cascade');
        });
    }
}
