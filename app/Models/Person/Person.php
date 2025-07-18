<?php

namespace App\Models\Person;


use App\Models\World\Life;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereName($value)
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Life[] $lives
 *
 * @mixin \Eloquent
 */
class Person extends \Eloquent
{
    protected $table = DB . '_person';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function lives(): HasMany { return $this->hasMany(Life::class, 'person_id', 'id')->orderBy('id'); }
}
