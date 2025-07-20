<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\World\Life;
use App\Requests\Person\PersonAddEventRequest;

class PersonEventAction
{
    public function __invoke(PersonAddEventRequest $request, int $id)
    {
        $life = Life::whereId($id)->first();

        $model = new PersonEvent();
        $model->life_id = $id;
        $model->person_id = $life->person_id;
        $model->type_id = $request->type;
        $model->begin = $request->begin;
        $model->end = $request->end;
        if ($request->comment) { $model->comment = $request->comment; }
        $model->save();

        $done = [];
        for ($ix = 1; $ix <= 111; $ix++) {
            if (!$request->get('connect_' . $ix) || !($connectLife = Life::whereId($request->get('connect_' . $ix))->first())) {
                continue;
            }
            if (in_array($connectLife->id, $done)) {
                continue;
            }
            $connect = new PersonEventConnect();
            $connect->life_id = $connectLife->id;
            $connect->person_id = $connectLife->person_id;
            $connect->event_id = $model->id;
            $connect->save();
            $done[] = $connectLife->id;
        }

        return redirect(route('web.person.details-life', ['person_id' => $life->person_id, 'life_id' => $life->id]));
    }
}
