<?php

namespace App\Models\Person;

/**
 * @property int $id
 * @property string $name
 * @property boolean $system
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|PersonType whereSystem($value)
 * @mixin \Eloquent
 */
class PersonType extends \Eloquent
{
    public const int SAPIENS = 1;
    public const int INVADERS = 2;

    protected $table = DB . '_type_person';
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
