<?php

namespace App\Models\World;

/**
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Planet whereName($value)
 * @mixin \Eloquent
 */
class Planet extends \Eloquent
{
    public const int HOME_PLANET = 1;

    protected $table = DB . '_planet';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function archive(): array
    {
        return ['name' => $this->name];
    }
}
