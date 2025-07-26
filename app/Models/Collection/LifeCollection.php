<?php

namespace App\Models\Collection;

use App\Models\World\Life;

class LifeCollection extends AbstractCollection
{
    public static function allodsByPersonID(int $personID, ?int $fromYear = null, ?int $untilYear = null): static
    {
        $query = Life::wherePersonId($personID)->whereType(Life::ALLODS);
        if ($fromYear > 0) {
            $query->where('begin', '>=', $fromYear);
        }
        if ($untilYear > 0) {
            $query->where('begin', '<=', $untilYear);
        }

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
}
