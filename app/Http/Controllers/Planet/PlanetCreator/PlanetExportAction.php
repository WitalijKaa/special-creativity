<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\EventType;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Work\Work;
use App\Models\World\Life;
use App\Models\World\Planet;
use Illuminate\Http\Request;

class PlanetExportAction
{
    public function __invoke(Request $request)
    {
        $planet = Planet::first();
        $work = Work::orderBy('begin')->get();
        $eventTypes = EventType::where('id', '>', EventType::HOLY_LIFE)->orderBy('id')->get();
        $persons = Person::orderBy('id')->get();
        $lives = Life::all();
        $events = PersonEvent::with(['connections.person', 'person', 'life'])->get();

        return view('planet.export', compact('planet', 'work', 'eventTypes', 'persons', 'lives', 'events'));
    }
}
