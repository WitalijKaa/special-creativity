<?php

namespace App\Migrations;

use App\Models\Person\ParentsType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ParentsTypeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_parents_type'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('system')->nullable(false)->default(false);
        });

        $model = new ParentsType();
        $model->id = ParentsType::WILD;
        $model->name = 'Wild';
        $model->system = true;
        $model->save();

        $model = new ParentsType();
        $model->id = ParentsType::SAPIENS;
        $model->name = 'Stvorio';
        $model->system = true;
        $model->save();

        $model = new ParentsType();
        $model->id = ParentsType::INVADERS;
        $model->name = 'Homo';
        $model->system = true;
        $model->save();

        $model = new ParentsType();
        $model->id = ParentsType::MIXED;
        $model->name = 'Mixed';
        $model->system = true;
        $model->save();
    }
}
