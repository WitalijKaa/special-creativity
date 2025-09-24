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
    public const int PLANET_LIFE_YOUNG_MAN = 3;
    public const int PLANET_LIFE_WOMAN = 4;
    public const int PLANET_LIFE_WOMAN_RARE = 5;
    public const int PLANET_LIFE_YOUNG_WOMAN = 6;

    protected $table = DB . '_type_force_event';
    public $timestamps = false;

    public static function forceByEffect(int $effect, Planet $planet): int
    {
        return match ($effect) {
            self::CREATE_PERSON => $planet->force_create * -1,
            self::PLANET_LIFE_MAN => $planet->force_man_up,
            self::PLANET_LIFE_YOUNG_MAN => $planet->force_man_first_up,
            self::PLANET_LIFE_YOUNG_WOMAN => $planet->force_woman_first_up,
            self::PLANET_LIFE_WOMAN => $planet->force_woman_up,
            self::PLANET_LIFE_WOMAN_RARE => $planet->force_woman_special_up,
        };
    }

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
