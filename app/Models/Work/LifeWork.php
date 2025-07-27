<?php

namespace App\Models\Work;

use App\Models\Collection\PersonCollection;
use App\Models\Person\PersonEvent;
use Illuminate\Support\Collection;

class LifeWork
{
    public int $begin;
    public int $end;
    public int $days = 0;
    public float $workYears = 0;

    /** @var array|\App\Models\Work\WorkOfLifeDto[][]|Collection */
    public Collection $works;

    /** @var array|\App\Models\Work\WorkOfLifeDto[][]|Collection */
    public Collection $events;

    /** @var array|\App\Models\Work\YearOfWorkEventOfPersonDto[][] */
    public array $worksPerYear = [];

    public static function calculateWork(Work $model): WorkCalculationsDto
    {
        $dto = new WorkCalculationsDto();
        $dto->workers = new PersonCollection();

        for ($year = $model->begin; $year <= $model->end; $year++) {
            $workers = new PersonCollection();
            $yearEvents = $model->events->filter(fn (PersonEvent $event) => $event->begin <= $year && $event->end >= $year)
                ->each(fn (PersonEvent $event) => $workers->pushUniqueWorkers($year, $event));

            if ($yearEvents->count()) {
                $yearDto = new YearOfWorkDto($workers);
                foreach ($yearDto->workers as $worker) {
                    $yearDto->days += $worker->days;
                }
                if (!$yearDto->days) {
                    continue;
                }
                $yearDto->workers->each(fn (PersonOfWorkDto $workerDto) => $dto->workers->pushUniqueWorker($workerDto));
                $dto->days += $yearDto->days;
                $dto->worksPerYear[$year] = $yearDto;

                if (empty($dto->begin)) { $dto->begin = $year; }
                $dto->end = $year;
            }
        }
        $dto->workYears = number_format($dto->days / WORK_DAYS, 2);
        return $dto;
    }

    public static function calculateLife(int $begin, int $end, Collection $workEvents): static
    {
        $lifeWork = new LifeWork();
        $lifeWork->works = new Collection();
        $lifeWork->events = new Collection();

        for ($year = $begin; $year <= $end; $year++) {
            $yearWorks = $workEvents->filter(fn (PersonEvent $event) => $event->begin <= $year && $event->end >= $year)
                ->map(fn (PersonEvent $event) => new YearOfWorkEventOfPersonDto($event, $event->work))
                ->values();

            if ($yearWorks->count()) {
                $lifeWork->worksPerYear[$year] = new YearOfWorkEventOfPersonCollection($yearWorks);
            }
        }

        $lifeWork->worksCalculate();
        return $lifeWork;
    }

    private function worksCalculate(): void
    {
        foreach ($this->worksPerYear as $year => $yearOfWork) {
            /** @var YearOfWorkEventOfPersonCollection $yearOfWork */

            $worksStrong = $yearOfWork->filter(fn (YearOfWorkEventOfPersonDto $dto) => $dto->event->strong);
            $worksElse = $yearOfWork->filter(fn (YearOfWorkEventOfPersonDto $dto) => !$dto->event->strong);

            $percentOfYear = 100;

            foreach ($worksStrong as $strongWork) {
                $percentForWork = $percentOfYear >= $strongWork->event->strong ? $strongWork->event->strong : $percentOfYear;
                if (!$percentForWork) {
                    break;
                }

                $strongWork->days = (int)(WORK_DAYS / 100 * $percentForWork);
                $strongWork->hours = $strongWork->days * WORK_HOURS;
                $this->trackWork($strongWork);

                $percentOfYear -= $percentForWork;
            }

            if (!$percentOfYear || !$worksElse->count() || !($percentForWork = (int)($percentOfYear / $worksElse->count()))) {
                continue;
            }

            foreach ($worksElse as $elseWork) {
                $elseWork->days = (int)(WORK_DAYS / 100 * $percentForWork);
                $elseWork->hours = $elseWork->days * WORK_HOURS;
                $this->trackWork($elseWork);
            }

            if (empty($this->begin)) { $this->begin = $year; }
            $this->end = $year;
        }
        $this->workYears = number_format($this->days / WORK_DAYS, 2);
    }

    private function trackWork(YearOfWorkEventOfPersonDto $workOfYear): void
    {
        $workOfLife = $this->works->filter(fn (WorkOfLifeDto $dto) => $dto->work->id == $workOfYear->work->id)->first();
        if (!$workOfLife) {
            $workOfLife = new WorkOfLifeDto($workOfYear->work);
            $this->works->push($workOfLife);
        }

        $eventOfLife = $this->events->filter(fn (WorkEventOfLifeDto $dto) => $dto->event->id == $workOfYear->event->id)->first();
        if (!$eventOfLife) {
            $eventOfLife = new WorkEventOfLifeDto($workOfYear->event);
            $this->events->push($eventOfLife);
        }

        $workOfLife->days += $workOfYear->days;
        $workOfLife->hours += $workOfYear->hours;
        $eventOfLife->days += $workOfYear->days;
        $eventOfLife->hours += $workOfYear->hours;

        $this->days += $workOfYear->days;
    }

    public function days(Work $ofWork): int
    {
        return (int)$this->works->filter(fn (WorkOfLifeDto $dto) => $dto->work->id == $ofWork->id)->first()?->days;
    }

    public function daysOfEvent(PersonEvent $ofWorkEvent): int
    {
        return (int)$this->events->filter(fn (WorkEventOfLifeDto $dto) => $dto->event->id == $ofWorkEvent->id)->first()?->days;
    }

    public function years(Work $ofWork): float
    {
        return number_format($this->days($ofWork) / WORK_DAYS, 2);
    }

    public function yearsOfEvent(PersonEvent $ofWorkEvent): float
    {
        return number_format($this->daysOfEvent($ofWorkEvent) / WORK_DAYS, 2);
    }

    public function percent(float $years): float
    {
        return number_format($years / $this->workYears * 100.0, 2);
    }

    public function hours(Work $ofWork): int
    {
        return (int)$this->works->filter(fn (WorkOfLifeDto $dto) => $dto->work->id == $ofWork->id)->first()?->hours;
    }

    public function daysOfWorkEventOfYear(int $year, int $workEventID): int
    {
        $return = 0;
        foreach (($this->worksPerYear[$year] ?? []) as $workDto) {
            /** @var YearOfWorkEventOfPersonDto $workDto */
            if ($workDto->event->id == $workEventID) {
                $return += $workDto->days;
            }
        }
        return $return;
    }
}
