<?php

namespace App\Http\Controllers\Person\Routine;

use App\Models\Collection\WorkCollection;
use App\Models\Person\EventType;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\Work\Work;
use App\Models\World\Configurator;
use App\Models\World\Life;

class WorkSlaveAction
{
    public function __invoke(int $id)
    {
        $life = Life::whereId($id)->first();

        $back = fn (string $field, string $msg) => redirect(route('web.person.details-life', ['life_id' => $id, 'person_id' => $life->person_id]))
            ->withErrors([$field => [$msg]]);

        // validations

        if (!$life?->person?->only_vizavi) {
            return $back('general', 'Not correct');
        }
        $vizaviLife = Life::wherePersonId($life->person->only_vizavi->id)
            ->whereBegin($life->begin)
            ->whereEnd($life->end)
            ->first();
        if (!$vizaviLife || $vizaviLife->is_woman) {
            return $back('general', 'Cant find vizavi life');
        }

        $works = WorkCollection::byYearsRange($life->begin, $life->end)->filterWorkArmy();

        if (!$works->count()) {
            return $back('general', 'Cant find works');
        }

        $conf = new Configurator();

        // save

        [$childYears, $adultYears] = $conf->workArmyLifeYearsRanges($life);

        foreach ($works as $work) {
            $this->addWorkEvent($life, $childYears[0], $childYears[1], 35, $work, [$vizaviLife]);
            $this->addWorkEvent($life, $adultYears[0], $adultYears[1], null, $work, [$vizaviLife]);
        }
        $conf = new Configurator();
        $this->makeEventByType($conf->manSlaveType(), $life, [$vizaviLife]);
        if ($life->prev_vs_type?->is_slave) {
            $this->makeEventByType(EventType::HOLY_LIFE, $life, [$vizaviLife]);
        }

        return redirect(route('web.person.details-life', ['person_id' => $life->person_id, 'life_id' => $life->id]));
    }

    private function addWorkEvent(Life $life, int $fromYear, int $untilYear, ?int $strong, Work $work, array $connections): void
    {
        if ($untilYear < $work->begin || $fromYear > $work->end) {
            return;
        }
        if ($fromYear < $work->begin) {
            $fromYear = $work->begin;
        }
        if ($untilYear > $work->end) {
            $untilYear = $work->end;
        }

        $conf = new Configurator();

        $model = new PersonEvent();
        $model->life_id = $life->id;
        $model->person_id = $life->person_id;
        $model->type_id = $conf->workArmySlaveType();
        $model->begin = $fromYear;
        $model->end = $untilYear;
        $model->work_id = $work->id;
        $model->strong = $strong;

        if (PersonEvent::wherePersonId($model->person_id)
            ->whereLifeId($model->life_id)
            ->whereBegin($model->begin)
            ->whereEnd($model->end)
            ->whereWorkId($model->work_id)
            ->exists())
        {
            return;
        }

        $model->save();

        foreach ($connections as $cLife) {
            /** @var $cLife Life */

            $connect = new PersonEventConnect();
            $connect->life_id = $cLife->id;
            $connect->person_id = $cLife->person_id;
            $connect->event_id = $model->id;
            $connect->save();
        }
    }

    private function makeEventByType(int $type, Life $life, array $connections): void
    {
        $conf = new Configurator();

        $model = new PersonEvent();
        $model->life_id = $life->id;
        $model->person_id = $life->person_id;
        $model->type_id = $type;
        $model->begin = $life->begin + $conf->workArmyLifeBeginAtAge();
        $model->end = $life->begin + $conf->workArmyLifeEndAtAge();

        if (PersonEvent::wherePersonId($model->person_id)
            ->whereLifeId($model->life_id)
            ->whereBegin($model->begin)
            ->whereEnd($model->end)
            ->whereTypeId($model->type_id)
            ->exists())
        {
            return;
        }

        $model->save();

        foreach ($connections as $cLife) {
            /** @var $cLife Life */

            $connect = new PersonEventConnect();
            $connect->life_id = $cLife->id;
            $connect->person_id = $cLife->person_id;
            $connect->event_id = $model->id;
            $connect->save();
        }
    }
}
