<?php

namespace App\Models\Collection;

use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventSynthetic;
use App\Models\World\Life;

class PersonEventCollection extends AbstractCollection
{
    public static function byLifeID(int $lifeID, array $with = []): static
    {
        $query = PersonEventBuilder::byLifeID($lifeID);
        if ($with) {
            $query->with($with);
        }
        return static::toCollection($query->get());
    }

    public static function byYearsRange(int $fromYear, int $untilYear): static
    {
        return static::toCollection(PersonEventBuilder::byYearsRange($fromYear, $untilYear)
            ->get())
            ->addSyntheticBackToAllodsEvents($fromYear, $untilYear)
            ->addSyntheticCreationsEvents($fromYear, $untilYear)
            ->addSyntheticBackToPlanetEvents($fromYear, $untilYear)
            ->sortNice();
    }

    public function filterWork(): static
    {
        return $this->filter(fn (PersonEvent $model) => $model->type->is_work);
    }

    public function addSyntheticBackToPlanetEvents(int $fromYear, int $untilYear): static
    {
        foreach (LifeCollection::planetByRange($fromYear, $untilYear) as $planetLife) {
            /** @var \App\Models\World\Life $planetLife */
            $this->push($planetLife->synthetic(PersonEventSynthetic::BIRTH, $planetLife->begin, $planetLife->end));
        }
        return $this;
    }

    public function addSyntheticBackToAllodsEvents(int $fromYear, int $untilYear): static
    {
        foreach (LifeCollection::allodsByRange($fromYear, $untilYear) as $allodsLife) {
            /** @var \App\Models\World\Life $allodsLife */
            $this->push($allodsLife->synthetic(PersonEventSynthetic::ALLODS, $allodsLife->begin, $allodsLife->end));
        }
        return $this;
    }

    public function addSyntheticBackToAllodsByPersonEvents(int $personID, ?int $fromYear = null, ?int $untilYear = null): static
    {
        foreach (LifeCollection::allodsByPersonID($personID, $fromYear, $untilYear) as $allodsLife) {
            /** @var \App\Models\World\Life $allodsLife */
            $this->push($allodsLife->synthetic(PersonEventSynthetic::ALLODS, $allodsLife->begin, $allodsLife->end));
        }
        return $this;
    }

    public function addSyntheticCreationsEvents(int $fromYear, int $untilYear): static
    {
        foreach (PersonCollection::byBeginRange($fromYear, $untilYear) as $person) {
            if ($person->id == Person::ORIGINAL) {
                continue;
            }
            /** @var \App\Models\Person\Person $person */
            $this->push($person->synthetic(PersonEventSynthetic::NEW_PERSON));
        }
        return $this;
    }

    public function addSyntheticCreationsByPersonEvents(int $personID, ?int $fromYear = null, ?int $untilYear = null): static
    {
        foreach (PersonCollection::byAuthorID($personID, $fromYear, $untilYear) as $person) {
            if ($person->id == Person::ORIGINAL) {
                continue;
            }
            /** @var \App\Models\Person\Person $person */
            $this->push($person->synthetic(PersonEventSynthetic::NEW_PERSON));
        }
        return $this;
    }

    public function addSyntheticBirthDeath(Life $model): static
    {
        if ($model->is_planet) {
            $this->unshift($model->synthetic(PersonEventSynthetic::BIRTH, $model->begin));
            $this->push($model->synthetic(PersonEventSynthetic::DEATH, $model->end));
        }
        return $this;
    }

    public function sortNice(): static
    {
        return $this->sort(function (PersonEvent|PersonEventSynthetic $modelA, PersonEvent|PersonEventSynthetic $modelB) {
            if ($modelA->begin == $modelB->begin) {
                return static::sortAlgorithmEventType($modelA->type, $modelB->type);
            }
            return $modelA->begin - $modelB->begin;

        })->values();
    }

    public function sortVsBegin(): static
    {
        return $this->sort(fn (PersonEvent|PersonEventSynthetic $modelA, PersonEvent|PersonEventSynthetic $modelB) => $modelA->begin - $modelB->begin);
    }

    public static function typesSorted(): \Illuminate\Database\Eloquent\Collection
    {
        return EventType::orderBy('id')
            ->get()
            ->sort(fn (EventType $modelA, EventType $modelB) => static::sortAlgorithmEventType($modelA, $modelB));
    }

    private static function sortAlgorithmEventType(EventType $modelA, EventType $modelB): int
    {
        if ($modelA->is_honor || $modelB->is_honor) {
            return (int)$modelB->is_honor - (int)$modelA->is_honor;
        }
        if ($modelA->is_relation || $modelB->is_relation) {
            return (int)$modelB->is_relation - (int)$modelA->is_relation;
        }
        if ($modelA->is_slave || $modelB->is_slave) {
            return (int)$modelB->is_slave - (int)$modelA->is_slave;
        }
        if ($modelA->is_work || $modelB->is_work) {
            return (int)$modelB->is_work - (int)$modelA->is_work;
        }
        return 0;
    }
}
