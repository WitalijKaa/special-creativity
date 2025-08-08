<?php

namespace App\Models\Collection;

use App\Models\Work\Work;
use App\Models\World\Configurator;

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

    public function filterWorkArmy(): static
    {
        $conf = new Configurator();
        return $this->filter(fn (Work $model) => $conf->isWorkArmy($model));
    }
}
