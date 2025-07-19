<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\Person;
use App\Models\World\Life;
use App\Models\World\Planet;
use Illuminate\Http\Request;

class PlanetExportAction
{
    public function __invoke(Request $request)
    {
        $planet = Planet::first();
        $persons = Person::orderBy('id')->get();
        $lives = Life::all();

        return view('planet.export', compact('planet', 'persons', 'lives'));
    }
}
