<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\Person\PersonEventSynthetic;

class PersonDetailsAction
{
    public function __invoke(int $id)
    {
        if (!$model = Person::whereId($id)->with(['lives.type'])->first()) {
            return redirect(route('web.person.list'));
        }

        $events = PersonEvent::wherePersonId($id)
            ->orWhereIn('id', PersonEventConnect::wherePersonId($id)->pluck('id'))
            ->with(['connections', 'type', 'person'])
            ->orderBy('begin')
            ->get();
//        $events->unshift($model->synthetic(PersonEventSynthetic::BIRTH, $model->begin));
//        $events->push($model->synthetic(PersonEventSynthetic::DEATH, $model->end));

        return view('person.details', compact('model', 'events'));
    }
}
