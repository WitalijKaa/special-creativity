<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\World\LifeType;
use App\Models\World\Planet;
use Illuminate\Http\Request;

class PlanetParamsAction
{
    public function __invoke(Request $request)
    {
        $planet = Planet::first();
        $lifeTypes = LifeType::orderByDesc('id')->get();

        return view('planet.params', compact('planet', 'lifeTypes'));
    }
}
