<?php

namespace App\Models\Collection;

use App\Models\World\Life;
use Illuminate\Database\Eloquent\Builder;

class LifeBuilder extends AbstractBuilder
{
    /** @return \Illuminate\Database\Eloquent\Builder|Life */
    public static function allodsByPersonID(int $personID, ?int $fromYear = null, ?int $untilYear = null): Builder
    {
        return static::whereBeginMaybeInRange(
            Life::wherePersonId($personID)->whereType(Life::ALLODS),
            $fromYear,
            $untilYear
        );
    }

    /** @return \Illuminate\Database\Eloquent\Builder|Life */
    public static function allodsByRange(int $fromYear, int $untilYear): Builder
    {
        return static::whereBeginEndInRange(
            Life::whereType(Life::ALLODS),
            $fromYear,
            $untilYear
        );
    }

    /** @return \Illuminate\Database\Eloquent\Builder|Life */
    public static function planetByRange(int $fromYear, int $untilYear): Builder
    {
        return static::whereBeginEndInRange(
            Life::whereType(Life::PLANET),
            $fromYear,
            $untilYear
        );
    }

    /** @return \Illuminate\Database\Eloquent\Builder|Life */
    public static function livedAtTheSameTime(Life $life): Builder
    {
        return static::whereBeginEndInRange(
            Life::where('person_id', '!=', $life->person_id)->whereType($life->type),
            $life->begin,
            $life->end
        );
    }
}

