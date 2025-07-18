<?php

namespace App\Http\Controllers\Planet\PlanerCreator;

use Illuminate\Http\Request;

class PlanetParamsAction
{
    public function __invoke(Request $request)
    {
        return view('planet.params');
    }
}
