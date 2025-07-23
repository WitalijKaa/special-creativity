<?php

namespace App\Models\Person;


use App\Models\World\Life;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $name
 * @property string $nick
 * @property int $force_person
 * @property int $begin
 * @property int|null $type
 * @property int|null $person_author_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereNick($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereForcePerson($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereBegin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Person whereType($value)
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
 * @property-read \Illuminate\Database\Eloquent\Collection|PersonEvent[] $events
 * @property-read \App\Models\Person\Person|null $author
 * @property-read \Illuminate\Support\Collection|\App\Models\Person\Person[] $vizavi
 * @property-read \App\Models\Person\Person $only_vizavi
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Person\Person[] $creations
 *
 * @mixin \Eloquent
 */
class Person extends \Eloquent
{
    public const int ORIGINAL = 1;
    public const int FORCE = 100;

    public const int IMPERIUM = 1;
    public const int WILD = 2;

    public const string TABLE_NAME = DB . '_person';
    protected $table = self::TABLE_NAME;

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
        return $this->lives->filter(fn (Life $model) => $model->is_planet && $model->is_man)->count();
    }

    public function getCountWomanLivesAttribute() // count_woman_lives
    {
        return $this->lives->filter(fn (Life $model) => $model->is_planet && $model->role == Life::WOMAN)->count();
    }

    public function livesBeforeReversed(int $lifeID): Collection
    {
        return $this->lives->filter(fn (Life $model) => $model->id < $lifeID)->reverse()->values();
    }

    private \Illuminate\Support\Collection $_vizavi;
    public function getVizaviAttribute() // vizavi
    {
        if (empty($this->_vizavi)) {
            $this->_vizavi = new \Illuminate\Support\Collection();
            $self = $this;

            $allConnects = PersonEventConnect::where(PersonEventConnect::TABLE_NAME . '.person_id', $this->id)
                ->join(PersonEvent::TABLE_NAME, fn (JoinClause $onClosure) => $onClosure
                    ->on(PersonEventConnect::TABLE_NAME . '.event_id', '=', PersonEvent::TABLE_NAME . '.id')
                    ->on(PersonEvent::TABLE_NAME . '.type_id', '=', DB::raw(EventType::DEEP_LOVE))
                )
                ->pluck('event_id');

            PersonEvent::where(fn (Builder $builder) => $builder->where('person_id', $this->id)->where('type_id', EventType::DEEP_LOVE))
                ->orWhereIn('id', $allConnects)
                ->with(['connections', 'person'])
                ->get()
                ->each(function (PersonEvent $event) use ($self) {
                    $ids = [];
                    foreach ($self->_vizavi as $vPerson) {
                        $ids[] = $vPerson->id;
                    }

                    if ($self->id != $event->person->id && !in_array($event->person->id, $ids)) {
                        $self->_vizavi->push($event->person);
                    }
                    foreach ($event->connections as $connectEvent) {
                        $connectPerson = $connectEvent->person;
                        if ($self->id != $connectPerson->id && !in_array($connectPerson->id, $ids)) {
                            $self->_vizavi->push($connectPerson);
                        }
                    }
                });
        }

        return $this->_vizavi;
    }

    public function getOnlyVizaviAttribute() // only_vizavi
    {
        if ($this->vizavi->count() == 1) {
            return $this->vizavi->first();
        }
        return null;
    }

    public function getMayBeGirlEasyAttribute() // may_be_girl_easy
    {
        if ($this->is_original && (!$this->lives->count() || !$this->count_woman_lives)) {
            return true;
        }

        return !$this->last_life?->is_planet &&
            $this->last_life?->role != Life::WOMAN &&
            $this->last_life?->may_be_girl_easy;
    }

    public $timestamps = false;
    protected $guarded = ['id'];
    public function lives(): HasMany { return $this->hasMany(Life::class, 'person_id', 'id')->orderBy('id'); }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany|\App\Models\Person\PersonEvent */
    public function events(): HasMany { return $this->hasMany(PersonEvent::class, 'person_id', 'id')->orderBy('id'); }
    public function creations(): HasMany { return $this->hasMany(Person::class, 'person_author_id', 'id')->orderBy('id'); }
    public function author(): BelongsTo { return $this->belongsTo(Person::class, 'person_author_id', 'id', 'creations'); }
    protected function casts(): array
    {
        return [
            'force_person' => 'integer',
            'begin' => 'integer',
        ];
    }

    public function archive(): array
    {
        return [
            'export' => 'person',
            'export_id' => $this->person_author_id ? $this->author->name : null,

            'name' => $this->name,
            'nick' => $this->nick,
            'begin' => $this->begin,
        ];
    }
}
