<?php

namespace App\Models\World;


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
class LifeType extends \Eloquent
{
    public const int ALLODS = 1;
    public const int PLANET = 2;

    protected $table = DB . '_type_life';
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
