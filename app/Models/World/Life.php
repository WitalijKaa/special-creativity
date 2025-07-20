<?php

namespace App\Models\World;


use App\Models\Person\Person;
use App\Models\Person\PersonEventSynthetic;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $begin
 * @property int $end
 * @property int $role
 * @property int $person_id
 * @property int $begin_force_person
 * @property int $parents_type_id
 * @property int|null $person_father_id
 * @property int|null $person_mother_id
 * @property int $type_id
 * @property int|null $planet_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBeginForcePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereParentsTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonFatherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonMotherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePlanetId($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|Life whereTypeId($value)
 *
 * @property-read string $role_name
 * @property-read bool $may_be_girl_easy
 *
 * @property-read \App\Models\World\LifeType $type
 * @property-read \App\Models\Person\Person $person
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\World\ForceEvent[] $forceEvents
 *
 * @mixin \Eloquent
 */
class Life extends \Eloquent
{
    public const int MAN = 1;
    public const int WOMAN = 2;
    public const int SPIRIT = 3;

    public const array ROLE = [self::MAN => 'MAN', self::WOMAN => 'WOMAN', self::SPIRIT => 'SPIRIT'];

    public const int PLANET_MAN_LIVES_TO_BE_GIRL_EASY = 4;

    protected $table = DB . '_life';

    public function getRoleNameAttribute() // role_name
    {
        return self::ROLE[$this->role];
    }

    public function getMayBeGirlEasyAttribute() // may_be_girl_easy
    {
        $lives = $this->person->livesBeforeReversed($this->id);

        if ($this->person->is_original && !$lives->filter(fn (Life $model) => $model->type_id == LifeType::PLANET && $model->role == Life::WOMAN)->count()) {
            return true;
        }

        $manLivesNonStop = 0;
        foreach ($this->person->livesBeforeReversed($this->id) as $life) {
            /** @var \App\Models\World\Life $life */
            if ($life->type_id != LifeType::PLANET) {
                continue;
            }

            if ($life->role != Life::MAN) {
                return false;
            }
            if (++$manLivesNonStop >= Life::PLANET_MAN_LIVES_TO_BE_GIRL_EASY) {
                return true;
            }
        }
        return false;
    }

    public function synthetic(int $type_id, int $begin, ?int $end = null): PersonEventSynthetic
    {
        $model = new PersonEventSynthetic();
        $model->type_id = $type_id;
        $model->life_id = $this->id;
        $model->person_id = $this->person_id;
        $model->begin = $begin;
        $model->end = $end ?? $begin;
        return $model;
    }

    public function archive(): array
    {
        return [
            'export' => 'life',
            'export_id' => $this->person->name,

            'begin' => $this->begin,
            'end' => $this->end,
            'type' => $this->type_id,
            'role' => $this->role,
            'parents' => $this->parents_type_id,
            'father' => null,
            'mother' => null,
        ];
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    public function type(): HasOne { return $this->hasOne(LifeType::class, 'id', 'type_id'); }
    public function person(): HasOne { return $this->hasOne(Person::class, 'id', 'person_id'); }
    public function forceEvents(): HasMany { return $this->hasMany(ForceEvent::class, 'life_id', 'id')->orderBy('id'); }
    protected function casts(): array
    {
        return [
            'begin' => 'integer',
            'end' => 'integer',
            'role' => 'integer',
        ];
    }

    public static function selectRoleOptions(): array
    {
        return [
            ['opt' => self::MAN, 'lbl' => self::ROLE[self::MAN]],
            ['opt' => self::WOMAN, 'lbl' => self::ROLE[self::WOMAN]],
            ['opt' => self::SPIRIT, 'lbl' => self::ROLE[self::SPIRIT]],
        ];
    }

    public static function selectConnectionOptions(Collection $lives): array
    {
        $return = $lives->sortBy('id')
            ->map(fn (self $model) => ['opt' => $model->id, 'lbl' => $model->person->name . ' ' . $model->begin . '-' . $model->end])
            ->toArray();
        return array_merge([['opt' => '0', 'lbl' => 'NoBody']], $return);
    }
}
