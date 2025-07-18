<?php

namespace App\Migrations;

use App\Models\World\LifeType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class LifeTypeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_life_type'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('system')->nullable(false)->default(false);
        });

        $model = new LifeType();
        $model->name = 'Allods';
        $model->system = true;
        $model->save();

        $model = new LifeType();
        $model->name = 'Planet';
        $model->system = true;
        $model->save();
    }
}
