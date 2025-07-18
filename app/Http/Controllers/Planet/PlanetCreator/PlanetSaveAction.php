<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\World\Planet;
use App\Requests\World\PlanetSaveRequest;

class PlanetSaveAction
{
    public function __invoke(PlanetSaveRequest $request)
    {
        $model = Planet::first() ?? new Planet();
        $model->name = $request->name;
        $model->save();

        return redirect(route('web.planet.params'));
    }
}
