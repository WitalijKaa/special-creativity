<?php

namespace App\Models\Collection;

use App\Models\Person\Person;
use Illuminate\Database\Eloquent\Builder;

class PersonBuilder extends AbstractBuilder
{
    /** @return \Illuminate\Database\Eloquent\Builder|Person */
    public static function byBeginRange(int $fromYear, int $untilYear): Builder
    {
        return Person::where('begin', '>=', $fromYear)
            ->where('begin', '<=', $untilYear)
            ->with('author.creations');
    }

    /** @return \Illuminate\Database\Eloquent\Builder|Person */
    public static function byAuthorID(int $authorID, ?int $fromYear = null, ?int $untilYear = null): Builder
    {
        $query = Person::wherePersonAuthorId($authorID)
            ->with('author.creations');
        static::whereBeginMaybeInRange($query, $fromYear, $untilYear);

        return $query;
    }
}

