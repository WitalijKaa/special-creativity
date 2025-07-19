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
class PersonType extends \Eloquent
{
    public const int SAPIENS = 1;
    public const int INVADERS = 2;

    protected $table = DB . '_person_type';
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
