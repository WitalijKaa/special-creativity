<?php

namespace App\Models\World;


use App\Models\Person\Person;

/**
 * @property int $id
 * @property string $name
 * @property boolean $system
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEventType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEventType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEventType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEventType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEventType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEventType whereSystem($value)
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
