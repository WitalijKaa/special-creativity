<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\Person;
use App\Models\Person\PersonType;
use App\Models\World\Planet;
use App\Requests\World\PlanetSaveRequest;

class PlanetSaveAction
{
    public function __invoke(PlanetSaveRequest $request)
    {
        $back = fn (string $field, string $msg) => redirect(route('web.planet.params'))
            ->withErrors([$field => [$msg]])
            ->withInput($request->toArray());

        $model = Planet::first() ?? new Planet();

        // validation

        if (!$request->person && !$model->id) {
            return $back('person', 'You should name a first Person too');
        }

        // save

        $model->name = $request->name;
        $model->save();

        if ($request->person) {
            $model = new Person();
            $model->id = Person::ORIGINAL;
            $model->name = $request->person;
            $model->type_id = PersonType::SAPIENS;
            $model->begin = 0;
            $model->force_person = Person::FORCE;
            $model->save();
        }

        return redirect(route('web.planet.params'));
    }
}
