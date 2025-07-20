<?php

namespace App\Models\Person;

/**
 * @property int $id
 * @property string $name
 * @property boolean $system
 * @property boolean $is_relation
 * @property boolean $is_work
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereSystem($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsRelation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|EventType whereIsWork($value)
 *
 * @mixin \Eloquent
 */
class EventType extends \Eloquent
{
    public const int DEEP_LOVE = 1;
    public const int ONCE_LOVE = 2;
    public const int MASS_LOVE = 4;
    public const int GREAT_FIGHT = 5;
    public const int SLAVE_JOB = 6;
    public const int HOLY_LIFE = 7;
    public const int SLAVE_WOMAN_LIFE = 8;
    public const int MOVE = 9;

    protected $table = DB . '_type_event';

    public $timestamps = false;
    protected $guarded = ['id'];
    protected function casts(): array
    {
        return [
            'system' => 'boolean',
            'is_relation' => 'boolean',
            'is_work' => 'boolean',
        ];
    }

    public static function selectOptions(): array
    {
        return static::orderBy('id')
            ->get()
            ->map(fn (self $model) => ['opt' => $model->id, 'lbl' => $model->name])
            ->toArray();
    }
}
