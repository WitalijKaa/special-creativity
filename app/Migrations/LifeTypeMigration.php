<?php

namespace App\Migrations;

use App\Models\World\LifeType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LifeTypeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_type_life'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('system')->nullable(false)->default(false);
        });

        $model = new LifeType();
        $model->id = LifeType::ALLODS;
        $model->name = 'Allods';
        $model->system = true;
        $model->save();

        $model = new LifeType();
        $model->id = LifeType::PLANET;
        $model->name = 'Planet';
        $model->system = true;
        $model->save();
    }
}
