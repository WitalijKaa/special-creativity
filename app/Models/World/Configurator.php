<?php

namespace App\Models\World;

use App\Models\Person\EventType;
use App\Models\Work\Work;

class Configurator
{
    // TODO all scalars later

    public function isWorkArmy(Work $model): bool
    {
        return str_starts_with($model->name, config('basic.workArmy.prefix'));
    }

    public function yearsToWorkAsStandardSupplyWorker(): int
    {
        return config('basic.standardSupplyWorkerYears');
    }

    public function workArmySlaveType(): int
    {
        return EventType::whereName(config('basic.workArmy.slaveType'))->firstOrFail()->id;
    }

    public function workArmyLifeBeginAtAge(): int
    {
        return config('basic.workArmy.minAge');
    }

    public function workArmyLifeEndAtAge(): int
    {
        return config('basic.workArmy.maxAge');
    }

    public function workArmyLifeYearsRanges(Life $life): array
    {
        $childYears = [
            $life->begin + config('basic.workArmy.childWorkStartAge'),
            $life->begin + config('basic.workArmy.childWorkEndAge')
        ];
        $adultYears = [
            $life->begin + config('basic.workArmy.adultWorkStartAge'),
            $life->end - $life->current_type_no
        ];
        return [$childYears, $adultYears];
    }

    public function manSlaveType(): int
    {
        return EventType::whereName(config('basic.eventTypes.manSlave'))->firstOrFail()->id;
    }
}
