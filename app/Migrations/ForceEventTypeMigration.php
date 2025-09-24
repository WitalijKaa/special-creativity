<?php

namespace App\Migrations;

use App\Models\World\ForceEventType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ForceEventTypeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_type_force_event'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('system')->nullable(false)->default(false);
        });

        $model = new ForceEventType();
        $model->id = ForceEventType::CREATE_PERSON;
        $model->name = 'theCreation';
        $model->system = true;
        $model->save();

        $model = new ForceEventType();
        $model->id = ForceEventType::PLANET_LIFE_MAN;
        $model->name = 'Man life';
        $model->system = true;
        $model->save();

        $model = new ForceEventType();
        $model->id = ForceEventType::PLANET_LIFE_YOUNG_MAN;
        $model->name = 'Young man life';
        $model->system = true;
        $model->save();

        $model = new ForceEventType();
        $model->id = ForceEventType::PLANET_LIFE_WOMAN;
        $model->name = 'Woman life';
        $model->system = true;
        $model->save();

        $model = new ForceEventType();
        $model->id = ForceEventType::PLANET_LIFE_WOMAN_RARE;
        $model->name = 'Nice-Girl life';
        $model->system = true;
        $model->save();

        $model = new ForceEventType();
        $model->id = ForceEventType::PLANET_LIFE_YOUNG_WOMAN;
        $model->name = 'Young woman life';
        $model->system = true;
        $model->save();
    }
}
