<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\World\Planet;
use Illuminate\Http\Request;

class PlanetParamsAction
{
    public function __invoke(Request $request)
    {
        $planet = Planet::first();

        return view('planet.params', compact('planet'));
    }
}
