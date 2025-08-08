<?php

namespace App\Models\Collection;

use App\Models\World\Life;

class LifeCollection extends AbstractCollection
{
    public static function allodsByPersonID(int $personID, ?int $fromYear = null, ?int $untilYear = null): static
    {
        $query = Life::wherePersonId($personID)->whereType(Life::ALLODS);
        AbstractBuilder::whereBeginMaybeInRange($query, $fromYear, $untilYear);

        return static::toCollection($query->get());
    }

    public static function allodsByRange(int $fromYear, int $untilYear): static
    {
        $query = AbstractBuilder::whereBeginEndInRange(
            Life::whereType(Life::ALLODS),
            $fromYear,
            $untilYear
        );

        return static::toCollection($query->get());
    }

    public static function planetByRange(int $fromYear, int $untilYear): static
    {
        $query = AbstractBuilder::whereBeginEndInRange(
            Life::whereType(Life::PLANET),
            $fromYear,
            $untilYear
        );

        return static::toCollection($query->get());
    }

    public static function livedAtTheSameTime(Life $life): static
    {
        $query = Life::where('person_id', '!=', $life->person_id)->whereType($life->type);

        return static::toCollection(AbstractBuilder::whereBeginEndInRange(
            $query,
            $life->begin,
            $life->end)
        ->get());
    }

    public function sortByBegin(): static
    {
        return $this->sortBy('begin');
    }

    public function sortByEnd(): static
    {
        return $this->sortBy('end');
    }
}
