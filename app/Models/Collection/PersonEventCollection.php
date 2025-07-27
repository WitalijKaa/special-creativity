<?php

namespace App\Models\Collection;

use App\Models\Person\EventType;
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

    public function filterWork(): static
    {
        return $this->filter(fn (PersonEvent $model) => $model->type->is_work);
    }

    public function addSyntheticBackToAllodsEvents(int $personID, ?int $fromYear = null, ?int $untilYear = null): static
    {
        foreach (LifeCollection::allodsByPersonID($personID, $fromYear, $untilYear) as $allodsLife) {
            $this->push($allodsLife->synthetic(PersonEventSynthetic::ALLODS, $allodsLife->begin, $allodsLife->end));
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
