<?php

namespace App\Models\Person;


use App\Models\World\Life;
use App\Models\World\LifeType;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property int $force_person
 * @property int $force_woman
 * @property int $begin
 * @property int|null $type_id
 * @property int|null $person_author_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereForcePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereForceWoman($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePersonAuthorId($value)
 *
 * @property-read boolean $is_original
 * @property-read int $count_man_lives
 * @property-read int $count_woman_lives
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|Life[] $lives
 * @property-read \App\Models\Person\PersonType $type
 * @property-read \App\Models\Person\Person|null $author
 *
 * @mixin \Eloquent
 */
class Person extends \Eloquent
{
    public const int ORIGINAL = 1;

    protected $table = DB . '_person';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function getIsOriginalAttribute() // is_original
    {
        return $this->id === self::ORIGINAL;
    }

    public function getCountManLivesAttribute() // count_man_lives
    {
        $this->lives->filter(fn (Life $model) => $model->type_id == LifeType::PLANET && $model->role == Life::MAN);
    }

    public function getCountWomanLivesAttribute() // count_woman_lives
    {
        $this->lives->filter(fn (Life $model) => $model->type_id == LifeType::PLANET && $model->role == Life::WOMAN);
    }

    public function lives(): HasMany { return $this->hasMany(Life::class, 'person_id', 'id')->orderBy('id'); }
    public function type(): HasOne { return $this->hasOne(PersonType::class, 'id', 'type_id'); }
    public function author(): HasOne { return $this->hasOne(static::class, 'id', 'person_author_id'); }
}
