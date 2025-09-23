<?php

namespace App\Http\Controllers;

use App\Models\Person\Person;
use App\Models\World\Planet;
use Illuminate\Http\Request;

class BasicAction
{
    public function __invoke(Request $request)
    {
        $planet = Planet::first();
        $persons = Person::count();

        return view('space.basic', compact('planet', 'persons'));
    }
}
