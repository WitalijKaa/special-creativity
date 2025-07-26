<?php

namespace App\Http\Controllers\Person;

use App\Models\Collection\LifeCollection;
use App\Models\Collection\PersonEventCollection;
use App\Models\Collection\WorkCollection;
use App\Models\World\Life;

class LifeDetailsAction
{
    public function __invoke(int $person_id, int $life_id)
    {
        if (!$model = Life::wherePersonId($person_id)->whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $connections = LifeCollection::livedAtTheSameTime($model);

        $events = PersonEventCollection::byLifeID($model->id)
            ->sortNice()
            ->addSyntheticBirthDeath($model);

        $work = WorkCollection::byYearsRange($model->begin, $model->end);

        return view('person.life-details', compact('model', 'connections', 'events', 'work'));
    }
}
