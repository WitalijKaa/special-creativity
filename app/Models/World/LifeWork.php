<?php

namespace App\Models\World;

use App\Models\Person\PersonEvent;
use App\Models\Work\WorkOfLifeDto;
use App\Models\Work\WorkOfYearCollection;
use App\Models\Work\WorkOfYearDto;
use Illuminate\Support\Collection;

class LifeWork
{
    /** @var array|\App\Models\Work\WorkOfLifeDto[][]|Collection */
    public Collection $works;
    /** @var array|\App\Models\Work\WorkOfYearDto[][] */
    public array $worksPerYear = [];

    public static function calculateLife(int $begin, int $end, Collection $workEvents): static
    {
        $lifeWork = new LifeWork();

        for ($year = $begin; $year <= $end; $year++) {
            $yearWorks = $workEvents->filter(fn (PersonEvent $event) => $event->begin <= $year && $event->end >= $year)
                ->map(fn (PersonEvent $event) => new WorkOfYearDto($event, $event->work))
                ->values();

            if ($yearWorks->count()) {
                $lifeWork->worksPerYear[$year] = new WorkOfYearCollection($yearWorks);
            }
        }

        foreach ($lifeWork->worksPerYear as $yearOfWork) {
            /** @var WorkOfYearCollection $yearOfWork */

            $worksStrong = $yearOfWork->filter(fn (WorkOfYearDto $dto) => $dto->event->strong);
            $worksElse = $yearOfWork->filter(fn (WorkOfYearDto $dto) => !$dto->event->strong);

            $percentOfYear = 100;

            foreach ($worksStrong as $strongWork) {
                $percentForWork = $percentOfYear >= $strongWork->event->strong ? $strongWork->event->strong : $percentOfYear;
                if (!$percentForWork) {
                    break;
                }

                $strongWork->days = (int)(WORK_DAYS / 100 * $percentForWork);
                $strongWork->hours = $strongWork->days * WORK_HOURS;
                $lifeWork->trackWork($strongWork);

                $percentOfYear -= $percentForWork;
            }

            if (!$percentOfYear || !$worksElse->count() || !($percentForWork = (int)($percentOfYear / $worksElse->count()))) {
                continue;
            }

            foreach ($worksElse as $elseWork) {
                $elseWork->days = (int)(WORK_DAYS / 100 * $percentForWork);
                $elseWork->hours = $elseWork->days * WORK_HOURS;
                $lifeWork->trackWork($elseWork);
            }
        }

        return $lifeWork;
    }

    public function trackWork(WorkOfYearDto $workOfYear): void
    {
        if (empty($this->works)) {
            $this->works = new Collection();
        }

        $workOfLife = $this->works->filter(fn (WorkOfLifeDto $dto) => $dto->work->id == $workOfYear->work->id)->first();
        if (!$workOfLife) {
            $workOfLife = new WorkOfLifeDto($workOfYear->work);
            $this->works->push($workOfLife);
        }

        $workOfLife->days += $workOfYear->days;
        $workOfLife->hours += $workOfYear->hours;
    }

    public function days(Work $ofWork): int
    {
        return (int)$this->works->filter(fn (WorkOfLifeDto $dto) => $dto->work->id == $ofWork->id)->first()?->days;
    }

    public function hours(Work $ofWork): int
    {
        return (int)$this->works->filter(fn (WorkOfLifeDto $dto) => $dto->work->id == $ofWork->id)->first()?->hours;
    }
}
