<?php

namespace App\Migrations;

use App\Models\Person\EventType;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EventTypeMigration implements MigratorInterface
{
    public static function tableName(): string { return DB . '_type_event'; }

    public static function migrate(): void
    {
        Schema::create(static::tableName(), function (Blueprint $table) {
            $table->id();
            $table->string('name', 64)->nullable(false);
            $table->boolean('system')->nullable(false)->default(false);
            $table->boolean('is_relation')->nullable(false)->default(false);
            $table->boolean('is_Work')->nullable(false)->default(false);
        });

        $model = new EventType();
        $model->id = EventType::DEEP_LOVE;
        $model->name = 'Deep Love';
        $model->system = true;
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::ONCE_LOVE;
        $model->name = 'Once Love';
        $model->system = true;
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::MASS_LOVE;
        $model->name = 'Mass Love';
        $model->system = true;
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::GREAT_FIGHT;
        $model->name = 'Epic fight';
        $model->system = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::SLAVE_JOB;
        $model->name = 'Slave man';
        $model->system = true;
        $model->is_work = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::HOLY_LIFE;
        $model->name = 'Holy Life';
        $model->system = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::SLAVE_WOMAN_LIFE;
        $model->name = 'Slave woman';
        $model->system = true;
        $model->is_work = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::MOVE;
        $model->name = 'Move';
        $model->system = true;
        $model->save();
    }
}
