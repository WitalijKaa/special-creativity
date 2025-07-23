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
            $table->boolean('is_honor')->nullable(false)->default(false);
            $table->boolean('is_relation')->nullable(false)->default(false);
            $table->boolean('is_work')->nullable(false)->default(false);
            $table->boolean('is_slave')->nullable(false)->default(false);
        });

        $model = new EventType();
        $model->id = EventType::DEEP_LOVE;
        $model->name = 'Deep Love';
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::ONCE_LOVE;
        $model->name = 'Once Love';
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::EMPTY_LOVE;
        $model->name = 'Empty Love';
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::DIRTY_LOVE;
        $model->name = 'Dirty fight';
        $model->is_relation = true;
        $model->save();

        $model = new EventType();
        $model->id = EventType::HOLY_LIFE;
        $model->name = 'Holy Life';
        $model->is_honor = true;
        $model->save();
    }
}
