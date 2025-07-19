<?php

namespace App\Models\Person;


use App\Models\World\Life;
use App\Models\World\LifeType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property int $force_person
 * @property int $begin
 * @property int|null $type_id
 * @property int|null $person_author_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereForcePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person wherePersonAuthorId($value)
 *
 * @property-read boolean $is_original
 * @property-read int $count_man_lives
 * @property-read int $count_woman_lives
 * @property-read bool $may_be_girl_easy
 * @property-read int $last_year
 *
 * @property-read Life|null $last_life
 * @property-read \Illuminate\Database\Eloquent\Collection|Life[] $lives
 * @property-read \App\Models\Person\PersonType $type
 * @property-read \App\Models\Person\Person|null $author
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person\Person[] $creations
 *
 * @mixin \Eloquent
 */
class Person extends \Eloquent
{
    public const int ORIGINAL = 1;
    public const int FORCE = 100;

    protected $table = DB . '_person';
    public $timestamps = false;

    protected $guarded = ['id'];

    public function getIsOriginalAttribute() // is_original
    {
        return $this->id === self::ORIGINAL;
    }

    public function getLastLifeAttribute() // last_life
    {
        return $this->lives->last();
    }

    public function getLastYearAttribute() // last_year
    {
        return $this->lives->last()?->end ?? $this->begin;
    }

    public function getCountManLivesAttribute() // count_man_lives
    {
        return $this->lives->filter(fn (Life $model) => $model->type_id == LifeType::PLANET && $model->role == Life::MAN)->count();
    }

    public function getCountWomanLivesAttribute() // count_woman_lives
    {
        return $this->lives->filter(fn (Life $model) => $model->type_id == LifeType::PLANET && $model->role == Life::WOMAN)->count();
    }

    public function livesBeforeReversed(int $lifeID): Collection
    {
        return $this->lives->filter(fn (Life $model) => $model->id < $lifeID)->reverse()->values();
    }

    public function getMayBeGirlEasyAttribute() // may_be_girl_easy
    {
        if ($this->is_original && (!$this->lives->count() || !$this->count_woman_lives)) {
            return true;
        }

        return $this->last_life?->type_id != LifeType::PLANET &&
            $this->last_life?->role != Life::WOMAN &&
            $this->last_life?->may_be_girl_easy;
    }

    public function lives(): HasMany { return $this->hasMany(Life::class, 'person_id', 'id')->orderBy('id'); }
    public function creations(): HasMany { return $this->hasMany(Person::class, 'person_author_id', 'id')->orderBy('id'); }
    public function type(): HasOne { return $this->hasOne(PersonType::class, 'id', 'type_id'); }
    public function author(): BelongsTo { return $this->belongsTo(Person::class, 'person_author_id', 'id', 'creations'); }

    public function archive(): array
    {
        return [
            'export' => 'person',
            'export_id' => $this->person_author_id ? $this->author->name : null,

            'name' => $this->name,
            'begin' => $this->begin,
        ];
    }
}
