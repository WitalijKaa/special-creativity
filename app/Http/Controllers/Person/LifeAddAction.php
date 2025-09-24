<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\World\ForceEvent;
use App\Models\World\Life;
use App\Models\World\Planet;
use App\Requests\Person\PersonAddLifeRequest;

class LifeAddAction
{
    public function __invoke(PersonAddLifeRequest $request, int $id)
    {
        $back = fn (string $field, string $msg) => redirect(route('web.person.details', ['id' => $id]))
            ->withErrors([$field => [$msg]])
            ->withInput($request->toArray());

        $person = Person::whereId($id)->first();
        /** @var Life $prevLife */
        $prevLife = $person->lives->last();

        // validations

        if ($request->begin >= $request->end) {
            return $back('end', 'End should be after Begin');
        }
        if (!$prevLife && $request->begin != $person->begin) {
            return $back('begin', 'First life should be Started at ' . $person->begin);
        }
        if ($prevLife && $request->begin != $prevLife->end) {
            return $back('begin', 'Next live should began after previous ' . $prevLife->end);
        }
        if ($prevLife && $request->type == $prevLife->type) {
            return $back('type', 'The same Life Type as the previous ' . Life::NAME[$request->type]);
        }

        // save

        $model = new Life();
        $model->begin = $request->begin;
        $model->end = $request->end;
        $model->role = $request->role;
        $model->type = $request->type;
        if ($model->is_planet) {
            $model->planet_id = Planet::HOME_PLANET;
        }
        $model->person_id = $id;
        $model->begin_force_person = $person->force_person;
        $model->save();

        ForceEvent::liveLife($person, $model, Planet::correctPlanet(), null, null)?->andSave();

        return redirect(route('web.person.details', ['id' => $id]));
    }
}
