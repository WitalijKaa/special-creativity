<?php

namespace App\Http\Controllers\Person\Routine;

use App\Models\Collection\LifeCollection;
use App\Models\Person\Person;
use App\Models\World\Life;

class CycleLifeAtAllodsAction
{
    public function __invoke()
    {
        $back = fn (string $field, string $msg) => redirect(route('web.basic.space'))
            ->withErrors([$field => [$msg]]);

        $persons = Person::all();

        $lives = new LifeCollection();
        foreach ($persons as $person) {
            $lastLife = $person->last_life;
            if ($lastLife && !$lastLife->is_planet) {
                return $back('general', 'Not correct');
            } else if ($lastLife) {
                $lives->push($lastLife);
            }
        }

        $lives = $lives->sortByEnd();
        $vizaviIDs = [];

        $firstPack = (int)($lives->count() * 0.05);
        if ($firstPack < 2) { $firstPack = 2; }
        if ($firstPack % 2) { $firstPack++; }

        foreach ($lives as $life) {
            /** @var $life Life */

            if (!$life->person->only_vizavi || in_array($life->person->id, $vizaviIDs)) {
                continue;
            }

            $addYears = mt_rand(4, 8);
            if ($firstPack > 0) {
                $addYears = 1;
                $firstPack -= 2;
            } else if ($life->current_type_no == 1) {
                $addYears = mt_rand(2, 3);
            } else if ($life->current_type_no == 2) {
                $addYears = mt_rand(2, 6);
            } else if ($life->current_type_no > 4) {
                $addYears = mt_rand(7, 11);
            }

            $model = new Life();
            $model->begin = $life->end;
            $model->end = $life->end + $addYears;
            $model->role = Life::SPIRIT;
            $model->type = Life::ALLODS;
            $model->person_id = $life->person_id;
            $model->begin_force_person = $life->person->force_person;

            $modelVizavi = new Life();
            $modelVizavi->begin = $life->end;
            $modelVizavi->end = $life->end + $addYears;
            $modelVizavi->role = Life::SPIRIT;
            $modelVizavi->type = Life::ALLODS;
            $modelVizavi->person_id = $life->person->only_vizavi->id;
            $modelVizavi->begin_force_person = $life->person->only_vizavi->force_person;

            $vizaviIDs[] = $life->person->only_vizavi->id;

            $model->save();
            $modelVizavi->save();
        }

        return redirect(route('web.basic.space'));
    }
}
