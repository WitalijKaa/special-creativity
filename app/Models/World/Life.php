<?php

namespace App\Models\World;

use App\Models\Collection\PersonEventBuilder;
use App\Models\Collection\PersonEventCollection;
use App\Models\Collection\WorkCollection;
use App\Models\Inteface\JsonArchivableInterface;
use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventSynthetic;
use App\Models\Poetry\Poetry;
use App\Models\Work\LifeWork;
use Illuminate\Database\Eloquent\Builder;
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
 * @property int|null $person_father_id
 * @property int|null $person_mother_id
 * @property int $type
 * @property int|null $planet_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life where($column, $operator = null, $value = null, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereEnd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereBeginForcePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonFatherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePersonMotherId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Life wherePlanetId($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|Life whereType($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|Life whereBegin($value)
 * @method \Illuminate\Database\Eloquent\Builder<static>|Life whereEnd($value)
 *
 * @property-read string $role_name
 * @property-read string $type_name
 * @property-read bool $is_man
 * @property-read bool $is_woman
 * @property-read bool $is_planet
 * @property-read bool $is_allods
 * @property-read bool $is_dream
 * @property-read bool $is_virtual
 * @property-read integer $current_type_no
 * @property-read bool $may_be_girl_easy
 * @property-read bool $is_holy
 * @property-read bool $is_deep_love
 * @property-read bool $is_slave
 * @property-read bool $is_worker
 *
 * @property-read \App\Models\Work\LifeWork $lifeWork
 * @property-read \App\Models\World\Life|null $prev_vs_type
 * @property-read \App\Models\World\Life|null $next_vs_type
 * @property-read \App\Models\Person\Person $person
 * @property-read \App\Models\Collection\PersonEventCollection|\App\Models\Person\PersonEvent[] $events
 * @property-read \App\Models\Collection\PersonEventCollection|\App\Models\Person\PersonEvent[] $work_events
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\World\ForceEvent[] $forceEvents
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry
 *
 * @mixin \Eloquent
 */
class Life extends \Eloquent implements JsonArchivableInterface
{
    public const int MAN = 1;
    public const int WOMAN = 2;
    public const int SPIRIT = 3;

    public const array ROLE = [self::MAN => 'MAN', self::WOMAN => 'WOMAN', self::SPIRIT => 'SPIRIT'];

    public const int ALLODS = 1;
    public const int PLANET = 2;
    public const int DREAM = 3;
    public const int VIRTUAL = 4;

    public const array NAME = [self::ALLODS => 'Allods', self::PLANET => 'Planet', self::DREAM => 'Dream', self::VIRTUAL => 'Virtual'];

    public const int PLANET_MAN_LIVES_TO_BE_GIRL_EASY = 4;

    public const string TABLE_NAME = DB . '_life';
    protected $table = self::TABLE_NAME;

    public function getRoleNameAttribute() // role_name
    {
        return self::ROLE[$this->role];
    }

    public function getTypeNameAttribute() // type_name
    {
        return self::NAME[$this->type];
    }

    public function getIsManAttribute() { return self::MAN == $this->role; }
    public function getIsWomanAttribute() { return self::WOMAN == $this->role; }
    public function getIsPlanetAttribute() { return self::PLANET == $this->type; }
    public function getIsAllodsAttribute() { return self::ALLODS == $this->type; }
    public function getIsDreamAttribute() { return self::DREAM == $this->type; }
    public function getIsVirtualAttribute() { return self::VIRTUAL == $this->type; }

    private LifeWork $_lifeWork;
    public function getLifeWorkAttribute() {
        if (empty($this->_lifeWork)) {
            $this->_lifeWork = LifeWork::calculateLife($this->begin, $this->end, $this->work_events);
        }
        return $this->_lifeWork;
    }

    public function getCurrentTypeNoAttribute() // current_type_no
    {
        return Life::wherePersonId($this->person_id)
            ->whereType($this->type)
            ->where('id', '<=', $this->id ?? 0)
            ->count();
    }

    public function getPrevVsTypeAttribute() // prev_vs_type
    {
        return Life::wherePersonId($this->person_id)
            ->whereType($this->type)
            ->where('id', '<', $this->id)
            ->orderByDesc('id')
            ->first();
    }

    public function getNextVsTypeAttribute() // next_vs_type
    {
        return Life::wherePersonId($this->person_id)
            ->whereType($this->type)
            ->where('id', '>', $this->id)
            ->orderBy('id')
            ->first();
    }

    public function getMayBeGirlEasyAttribute() // may_be_girl_easy
    {
        $lives = $this->person->livesBeforeReversed($this->id ?? 0);

        if ($this->person->is_original && !$lives->filter(fn (Life $model) => $model->is_planet && $model->is_woman)->count()) {
            return true;
        }

        $manLivesNonStop = 0;
        foreach ($this->person->livesBeforeReversed($this->id ?? 0) as $life) {
            /** @var \App\Models\World\Life $life */
            if (!$life->is_planet) {
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

    private PersonEventCollection $_events;
    public function getEventsAttribute() // events
    {
        if (empty($this->_events)) {
            $this->_events = PersonEventCollection::byLifeID($this->id, ['type', 'work']);
        }
        return PersonEventCollection::toCollection($this->_events);
    }

    public function getWorkEventsAttribute() // work_events
    {
        return $this->events->filterWork();
    }

    public function getIsHolyAttribute() // is_holy
    {
        return PersonEventBuilder::byLifeID($this->id)->whereTypeId(EventType::HOLY_LIFE)->exists();
    }

    public function getIsDeepLoveAttribute() // is_deep_love
    {
        return PersonEventBuilder::byLifeID($this->id)->whereTypeId(EventType::DEEP_LOVE)->exists();
    }

    public function getIsSlaveAttribute() // is_slave
    {
        return PersonEventBuilder::slaveByLifeId($this->id)->exists();
    }

    public function getIsWorkerAttribute() // is_worker
    {
        if ($this->is_slave) {
            return false;
        }

        $works = WorkCollection::byYearsRange($this->begin, $this->end)->filterWorkArmy()->pluck('id')->toArray();
        $worksLength = 0;

        !!$this->events->each(function (PersonEvent $model) use (&$worksLength, $works) {
                if ($model->strong > 50 && in_array($model->work_id, $works)) {
                    $worksLength += $model->length;
                }
            });

        return $worksLength > (new Configurator())->yearsToWorkAsStandardSupplyWorker();
    }

    public function synthetic(int $type_id, int $begin, ?int $end = null): PersonEventSynthetic
    {
        $model = new PersonEventSynthetic();
        $model->type_id = $type_id;
        $model->life_id = $this->id;
        $model->person_id = $this->person_id;
        $model->begin = $begin;
        $model->end = $end ?? $begin;
        if (PersonEventSynthetic::BIRTH == $type_id) {
            $model->comment = 'no.' . $this->current_type_no;
        }
        return $model;
    }

    public function archive(): array
    {
        return [
            'person' => $this->person->name,

            'begin' => $this->begin,
            'end' => $this->end,
            'type' => $this->type,
            'role' => $this->role,
            'begin_force_person' => $this->begin_force_person,
        ];
    }

    public static function fromArchive(array $archive): void
    {
        $archive['person_id'] = Person::whereName($archive['person'])->first()->id;
        static::create($archive);
    }

    public static function getByPersonNameLifeTypeYears(string $name, int $type, int $beginMax, int $endMin): Life
    {
        return static::wherePersonId(Person::whereName($name)->first()->id)
            ->whereType($type)
            ->where(function (Builder $builder) use ($beginMax, $endMin) {
                return $builder->where('begin', '<=', $beginMax)->where('end', '>=', $endMin);
            })
            ->first();
    }

    public function poetrySpecific(string $lang, ?string $llm): \Illuminate\Database\Eloquent\Collection
    {
        return Poetry::whereLifeId($this->id)
            ->whereLang($lang)
            ->whereAi($llm)
            ->orderBy('ix_text')
            ->get();
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    public function person(): HasOne { return $this->hasOne(Person::class, 'id', 'person_id'); }
    public function forceEvents(): HasMany { return $this->hasMany(ForceEvent::class, 'life_id', 'id')->orderBy('id'); }
    public function poetry(): HasMany { return $this->hasMany(Poetry::class, 'life_id', 'id')
        ->where('lang', config('basic.lang'))
        ->whereNull('ai')
        ->orderBy('ix_text'); }
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

    public static function selectTypeOptions(): array
    {
        return [
            ['opt' => self::ALLODS, 'lbl' => self::NAME[self::ALLODS]],
            ['opt' => self::PLANET, 'lbl' => self::NAME[self::PLANET]],
            // ['opt' => self::DREAM, 'lbl' => self::NAME[self::DREAM]],
            // ['opt' => self::VIRTUAL, 'lbl' => self::NAME[self::VIRTUAL]],
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
