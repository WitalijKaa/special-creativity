<?php

namespace App\Models\Person;


use App\Models\World\LifeType;

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
class ParentsType extends \Eloquent
{
    public const int WILD = 1;
    public const int SAPIENS = 2;
    public const int INVADERS = 3;
    public const int MIXED = 4;

    protected $table = DB . '_type_parents';
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
