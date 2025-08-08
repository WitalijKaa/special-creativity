<?php

namespace App\Models\World;

use App\Models\Person\EventType;
use App\Models\Work\Work;

class Configurator
{
    // TODO all scalars later

    public function isWorkArmy(Work $model): bool
    {
        return str_starts_with($model->name, 'T.army');
    }

    public function workArmySlaveType(): int
    {
        return EventType::whereName('Tribe Work')->first()->id;
    }

    public function workArmyLifeBeginAtAge(): int
    {
        return 17;
    }

    public function workArmyLifeEndAtAge(): int
    {
        return 53;
    }

    public function workArmyLifeYearsRanges(Life $life): array
    {
        $childYears = [$life->begin + 7, $life->begin + 10];
        $adultYears = [$life->begin + 11, $life->end - $life->current_type_no];
        return [$childYears, $adultYears];
    }

    public function manSlaveType(): int
    {
        return EventType::whereName('Man Slave')->first()->id;
    }
}
