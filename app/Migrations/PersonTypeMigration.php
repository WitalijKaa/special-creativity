<?php

namespace App\Migrations;

use App\Models\Person\ParentsType;
use App\Models\Person\PersonType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PersonTypeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_type_person'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('system')->nullable(false)->default(false);
        });

        $model = new PersonType();
        $model->id = PersonType::SAPIENS;
        $model->name = 'Stvorio';
        $model->system = true;
        $model->save();

        $model = new PersonType();
        $model->id = PersonType::INVADERS;
        $model->name = 'Homo';
        $model->system = true;
        $model->save();
    }
}
