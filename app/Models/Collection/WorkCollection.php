<?php

namespace App\Models\Collection;

use App\Models\World\Work;

class WorkCollection extends AbstractCollection
{
    public static function byYearsRange(int $fromYear, int $untilYear): static
    {
        return static::toCollection(AbstractBuilder::whereBeginEndInRange(
            Work::query(),
            $fromYear,
            $untilYear)
        ->get());
    }
}
