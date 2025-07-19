<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\World\ForceEvent;
use App\Models\World\Life;
use App\Models\World\LifeType;
use App\Models\World\Planet;
use App\Requests\Person\PersonAddLifeRequest;

class PersonLifeAction
{
    public function __invoke(PersonAddLifeRequest $request, int $id)
    {
        $person = Person::whereId($id)->first();
        $prevLife = $person->lives->last();

        // validations

        $back = fn (string $field, string $msg) => redirect(route('web.person.details', ['id' => $id]))
            ->withErrors([$field => [$msg]])
            ->withInput($request->toArray());

        if ($request->begin >= $request->end) {
            return $back('end', 'End should be after Begin');
        }
        if (!$prevLife && $request->begin != $person->begin) {
            return $back('begin', 'First life should be Started at ' . $person->begin);
        }
        if ($prevLife && $request->begin != $prevLife->end) {
            return $back('begin', 'Next live should began after previous ' . $prevLife->end);
        }
        if ($prevLife && $request->type == $prevLife->id) {
            return $back('type', 'The same Life Type as the previous ' . LifeType::whereId($request->type)->first()?->name);
        }

        // save

        $model = new Life();
        $model->begin = $request->begin;
        $model->end = $request->end;
        $model->role = $request->role;
        $model->type_id = $request->type;
        if (LifeType::PLANET == $model->type_id) {
            $model->planet_id = Planet::HOME_PLANET;
        }
        $model->person_id = $id;
        $model->begin_force_person = $person->force_person;
        $model->parents_type_id = $request->parents;
        $model->save();

        ForceEvent::liveLife($person, $model);

        return redirect(route('web.person.details', ['id' => $id]));
    }
}
