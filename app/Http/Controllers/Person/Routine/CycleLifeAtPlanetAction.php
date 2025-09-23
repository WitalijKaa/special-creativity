<?php

namespace App\Http\Controllers\Person\Routine;

use App\Models\Collection\LifeCollection;
use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\World\ForceEvent;
use App\Models\World\Life;
use App\Models\World\Planet;

class CycleLifeAtPlanetAction
{
    public function __invoke()
    {
        $back = fn (string $field, string $msg) => redirect(route('web.basic.space'))
            ->withErrors([$field => [$msg]]);

        $persons = Person::all();

        $lives = new LifeCollection();
        foreach ($persons as $person) {
            $lastLife = $person->last_life;
            if ($lastLife && !$lastLife->is_allods) {
                return $back('general', 'Not correct');
            }

            if (!$lastLife) {
                $lastLife = new Life();
                $lastLife->end = $person->begin;
                $lastLife->person_id = $person->id;
            }

            $lives->push($lastLife);
        }

        $lives = $lives->sortByEnd();
        $vizaviIDs = [];

        $firstPack = (int)($lives->count() * 0.05);
        if ($firstPack < 2) { $firstPack = 2; }
        if ($firstPack % 2) { $firstPack++; }

        foreach ($lives as $life) {
            /** @var $life Life */

            if (in_array($life->person->id, $vizaviIDs)) {
                continue;
            }

            $vizavi = $life->person->only_vizavi;
            $needLove = false;

            if (!$vizavi && !$life->id) {
                $vizavi = Person::whereId($life->person->id + 1)
                    ->whereBegin($life->end)
                    ->first();
                $needLove = true;

                if ($vizavi?->only_vizavi) {
                    $vizavi = null;
                }
            }

            if (!$vizavi) {
                continue;
            }

            $addYears = mt_rand(62, 74);
            if ($firstPack > 0) {
                $addYears = mt_rand(61, 65);
                $firstPack -= 2;
            } else if ($life->current_type_no == 1) {
                $addYears = mt_rand(65, 72);
            } else if ($life->current_type_no == 2) {
                $addYears = mt_rand(68, 76);
            } else if ($life->current_type_no > 7) {
                $addYears = mt_rand(62, 67);
            }

            $isFirstGirl = $life->may_be_girl_easy;
            $isSecondGirl = !$isFirstGirl && $vizavi->last_life?->may_be_girl_easy;

            $model = new Life();
            $model->begin = $life->end;
            $model->end = $life->end + $addYears;
            $model->role = $isFirstGirl ? Life::WOMAN : Life::MAN;
            $model->type = Life::PLANET;
            $model->planet_id = Planet::HOME_PLANET;
            $model->person_id = $life->person_id;
            $model->begin_force_person = $life->person->force_person;

            $modelVizavi = new Life();
            $modelVizavi->begin = $life->end;
            $modelVizavi->end = $life->end + $addYears;
            $modelVizavi->role = $isSecondGirl ? Life::WOMAN : Life::MAN;
            $modelVizavi->type = Life::PLANET;
            $modelVizavi->planet_id = Planet::HOME_PLANET;
            $modelVizavi->person_id = $vizavi->id;
            $modelVizavi->begin_force_person = $vizavi->force_person;

            $vizaviIDs[] = $vizavi->id;

            $model->save();
            $modelVizavi->save();
            ForceEvent::liveLife($life->person, $model);
            ForceEvent::liveLife($vizavi, $modelVizavi);

            if ($needLove) {
                $event = new PersonEvent();
                $event->life_id = $model->id;
                $event->person_id = $model->person_id;
                $event->type_id = EventType::DEEP_LOVE;
                $event->begin = $model->begin + 13;
                $event->end = $model->begin + 45;
                $event->save();

                $connect = new PersonEventConnect();
                $connect->life_id = $modelVizavi->id;
                $connect->person_id = $modelVizavi->person_id;
                $connect->event_id = $event->id;
                $connect->save();
            }
        }

        return redirect(route('web.basic.space'));
    }
}
