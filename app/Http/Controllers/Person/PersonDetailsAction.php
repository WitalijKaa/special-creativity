<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\Person\PersonEventSynthetic;
use App\Models\World\Life;

class PersonDetailsAction
{
    public function __invoke(int $id)
    {
        if (!$model = Person::whereId($id)->with(['lives'])->first()) {
            return redirect(route('web.person.list'));
        }

        $events = PersonEvent::wherePersonId($id)
            ->orWhereIn('id', PersonEventConnect::wherePersonId($id)->pluck('event_id')->unique())
            ->with(['connections.life', 'type', 'person'])
            ->get();

        foreach (Life::wherePersonId($id)->whereType(Life::ALLODS)->get() as $allodsLife) {
            $events->push($allodsLife->synthetic(PersonEventSynthetic::ALLODS, $allodsLife->begin, $allodsLife->end));
        }
        $events = $events->sortBy('begin')->values();

        return view('person.details', compact('model', 'events'));
    }
}
