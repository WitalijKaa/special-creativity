<?php

namespace App\Models\World;


use App\Models\Person\Person;

/**
 * @property int $id
 * @property string $name
 * @property boolean $system
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LifeType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LifeType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LifeType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LifeType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LifeType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LifeType whereSystem($value)
 * @mixin \Eloquent
 */
class ForceEventType extends \Eloquent
{
    public const int CREATE_PERSON = 1;
    public const int PLANET_LIFE_MAN = 2;
    public const int PLANET_LIFE_MAN_AT_BEGINNING = 3;
    public const int PLANET_LIFE_WOMAN = 4;
    public const int PLANET_LIFE_WOMAN_RARE = 5;

    public const DIFF_PERSON = [
        self::CREATE_PERSON => -95,
        self::PLANET_LIFE_MAN => 100,
        self::PLANET_LIFE_MAN_AT_BEGINNING => 25,
        self::PLANET_LIFE_WOMAN => 35,
        self::PLANET_LIFE_WOMAN_RARE => 95,
    ];

    protected $table = DB . '_type_force_event';
    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'system' => 'boolean',
        ];
    }

    protected $guarded = ['id'];

    public static function selectOptions(): array
    {
        return static::orderBy('id')
            ->get()
            ->map(fn (self $model) => ['opt' => $model->id, 'lbl' => $model->name])
            ->toArray();
    }
}
