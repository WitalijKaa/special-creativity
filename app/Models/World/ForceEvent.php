<?php

namespace App\Models\World;

use App\Models\Collection\LifeBuilder;
use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property int $diff
 * @property int|null $year
 * @property int $person_id
 * @property int $life_id
 * @property int $type_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereDiff($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereLifeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent wherePersonId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ForceEvent whereYear($value)
 * @mixin \Eloquent
 *
 * @property-read \App\Models\World\ForceEventType $type
 *
 */
class ForceEvent extends \Eloquent
{
    protected $table = DB . '_event_force';
    public $timestamps = false;

    protected $guarded = ['id'];

    private ?Person $_person_of_force_effect = null;
    public function andSave(): void // its for > static function createPerson AND static function liveLife
    {
        $this->save();
        $this->_person_of_force_effect->save();
        $this->_person_of_force_effect = null;
    }

    public static function canHeCreatePerson(Person $author): bool
    {
        return $author->force_person >= Person::FORCE;
    }

    public static function createPerson(Person $author, int $year): static
    {
        $planet = Planet::correctPlanet();
        static::applyForceEffect(static::creationForceEffect($planet), $author);

        $event = new static();
        $event->type_id = ForceEventType::CREATE_PERSON;
        $event->diff = static::creationForceEffect($planet);
        $event->person_id = $author->id;
        $event->life_id = LifeBuilder::allodsByRange($year, $year)->wherePersonId($author->id)->first()->id;
        $event->year = $year;
        $event->_person_of_force_effect = $author;
        return $event;
    }

    public static function liveLife(Person $person, Life $life, Planet $planet, ?bool $personEverHadFullForce, ?bool $wasWomanRareLifeAllowed): ?static
    {
        if (!$life->is_planet) { return null; }
        if (is_null($personEverHadFullForce)) { $personEverHadFullForce = $life->ever_has_had_force_full; }
        if (is_null($wasWomanRareLifeAllowed)) { $wasWomanRareLifeAllowed = $life->mayBeGirlEasy($planet); }

        $forceEffect = static::materialLifeForceEffect($life, $planet, $personEverHadFullForce, $wasWomanRareLifeAllowed);
        if ($forceEffect) {
            static::applyForceEffect($forceEffect->diff, $person);
            $forceEffect->_person_of_force_effect = $person;
        }
        return $forceEffect;
    }

    public function diffSimple(int $force): int
    {
        $force += $this->diff;
        if ($force < 0) { $force = 0; }
        if ($force > Person::FORCE) { $force = Person::FORCE; }
        return $force;
    }

    private static function materialLifeForceEffect(Life $life, Planet $planet, bool $personEverHadFullForce, bool $wasWomanRareLifeAllowed): ?static
    {
        if ($life->is_man && $life->length_years >= $planet->force_man_min) {
            $forceType = $personEverHadFullForce ? ForceEventType::PLANET_LIFE_MAN : ForceEventType::PLANET_LIFE_YOUNG_MAN;
            $forceEffect = ForceEventType::forceByEffect($forceType, $planet);
        }
        else if ($life->is_woman && $life->length_years >= $planet->force_woman_min) {
            $forceType = $personEverHadFullForce ? ($wasWomanRareLifeAllowed ? ForceEventType::PLANET_LIFE_WOMAN_RARE : ForceEventType::PLANET_LIFE_WOMAN) :
                ForceEventType::PLANET_LIFE_YOUNG_WOMAN;
            $forceEffect = ForceEventType::forceByEffect($forceType, $planet);
        }
        if (!empty($forceType)) {
            $event = new static();
            $event->type_id = $forceType;
            $event->diff = $forceEffect;
            $event->person_id = $life->person_id;
            $event->life_id = $life->id;
            $event->year = $life->end;
            return $event;
        }
        return null;
    }

    private static function creationForceEffect(Planet $planet): int
    {
        return ForceEventType::forceByEffect(ForceEventType::CREATE_PERSON, $planet);
    }

    private static function applyForceEffect(?int $forceEffect, Person $person): void
    {
        if (!is_null($forceEffect)) {
            $person->force_person += $forceEffect;
            if ($person->force_person < 0) { $person->force_person = 0; }
            if ($person->force_person > Person::FORCE) { $person->force_person = Person::FORCE; }
        }
    }

    public static function reWriteCreationsAndLives(): void
    {
        static::recalculateCreationsAndForce();
        static::recalculateCreationsAndForce(true);
    }

    public static function recalculateCreationsAndForce(bool $doUpdate = false): void
    {
        $year = 0;
        $planet = Planet::correctPlanet();
        [$persons, $personsById] = static::personsForCalculations();
        $planetLives = static::livesForCalculations();
        $beginForcePerson = [$year => [[Person::ORIGINAL => [Life::ALLODS, $personsById[Person::ORIGINAL]->force_person]]]];
        $forceEvents = [];

        $lastYear = Life::orderByDesc('end')->first()->end;
        do {
            $beginForcePerson[$year] = $beginForcePerson[$year] ?? [];
            foreach ($persons as $person) {
                /** @var \App\Models\Person\Person $person */
                if ($person->begin > $year) {
                    continue;
                }

                if (!empty($planetLives[$person->id][$year])) {
                    /** @var \App\Models\World\Life $life */
                    $life = $planetLives[$person->id][$year];
                    $wasWomanRareLifeAllowed = static::calcPreviousManLivesRow($life->end, $planetLives[$person->id]) >= $planet->force_woman_man_allowed ||
                        !$person->sim_origin_rare_girl;

                    $forceEvents[] = static::liveLife($person, $life, $planet, $person->sim_got_hundred_force, $wasWomanRareLifeAllowed);

                    if ($person->force_person == 100) {
                        $person->sim_got_hundred_force = true;
                    }
                    if ($life->is_woman) {
                        $person->sim_origin_rare_girl = true;
                    }
                }

                if ($person->begin == $year && $person->person_author_id) {
                    /** @var \App\Models\Person\Person $author */
                    $author = $personsById[$person->person_author_id];

                    if (!static::canHeCreatePerson($author)) {
                        throw new \Exception('Cant create a person');
                    }
                    $forceEvents[] = static::createPerson($author, $year);
                }
            }

            if ($doUpdate) {
                foreach ($persons as $person) {
                    $beginForcePerson[$year] = array_merge($beginForcePerson[$year], static::calcBeginForcePerson($year, $planetLives[$person->id], $person->force_person));
                }
            }

            $year++;
        } while ($year < $lastYear + 1111);

        if ($doUpdate) {
            static::saveCalculationToDB($persons, $beginForcePerson, $forceEvents);
        }
    }

    private static function saveCalculationToDB(array $persons, array $beginForcePerson, array $forceEvents): void
    {
        foreach ($persons as $person) {
            Person::whereId($person->id)->update(['force_person' => $person->force_person]);
        }

        foreach ($beginForcePerson as $year => $items) {
            foreach ($items as $item) {
                $personID = array_key_first($item);
                $lifeType = $item[$personID][0];
                $forceAtBeginning = $item[$personID][1];
                Life::wherePersonId($personID)->whereBegin($year)->whereType($lifeType)
                    ->update(['begin_force_person' => $forceAtBeginning]);
            }
        }

        static::truncate();
        foreach ($forceEvents as $event) {
            $event->save();
        }
    }

    private static function personsForCalculations(): array
    {
        $persons = [];
        $personsById = [];
        foreach (Person::orderBy('id')->get() as $person) {
            $model = new Person($person->toArray());
            $model->id = $person->id;
            $model->nick = null; // now we cant save()

            $model->force_person = 0;

            $model->sim_got_hundred_force = false;
            $model->sim_origin_rare_girl = true;

            if ($model->id == Person::ORIGINAL) {
                $model->force_person = Planet::correctPlanet()->force_at_start;
                $model->sim_got_hundred_force = true;
                $model->sim_origin_rare_girl = false;
            }

            $personsById[$model->id] = $model;
            $persons[] = $model;
        }
        return [$persons, $personsById];
    }

    private static function livesForCalculations(): array
    {
        $planetLives = [];
        foreach (Life::whereType(Life::PLANET)->orderBy('end')->get() as $life) {
            $model = new Life($life->toArray());
            $model->begin_force_person = null; // now we cant save()

            $planetLives[$life->person_id][$life->end] = $life;
        }
        return $planetLives;
    }

    private static function calcPreviousManLivesRow(int $currentLifeEnd, array $planetLives): int
    {
        $prevLives = array_filter($planetLives, fn (Life $life) => $life->end < $currentLifeEnd);
        usort($prevLives, fn (Life $lifeA, Life $lifeB) => $lifeB <=> $lifeA); // reverse
        $manBefore = 0;
        foreach ($prevLives as $pLife) {
            if ($pLife->role == Life::WOMAN) {
                break;
            }
            $manBefore++;
        }
        return $manBefore;
    }

    private static function calcBeginForcePerson(int $year, array $planetLives, int $force): array
    {
        $forces = [];
        foreach ($planetLives as $life) {
            /** @var \App\Models\World\Life $life */

            if ($year == $life->begin) {
                $forces[] = [$life->person_id => [Life::PLANET, $force]];
            } else if ($year == $life->end) {
                $forces[] = [$life->person_id => [Life::ALLODS, $force]];
            }
        }
        return $forces;
    }

    public function type(): HasOne { return $this->hasOne(ForceEventType::class, 'id', 'type_id'); }
}
