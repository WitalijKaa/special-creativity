<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\Person;
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

        if (!$model->id && (!$request->person || !$request->nick)) {
            return $back('person', 'You should provide a first Person too');
        }

        // save

        $model->name = $request->name;
        $model->save();

        if ($request->person) {
            $model = new Person();
            $model->id = Person::ORIGINAL;
            $model->name = $request->person;
            $model->nick = $request->nick;
            $model->type = Person::IMPERIUM;
            $model->begin = 0;
            $model->force_person = Person::FORCE;
            $model->save();
        }

        return redirect(route('web.planet.params'));
    }
}
