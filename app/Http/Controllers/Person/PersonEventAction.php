<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\EventType;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\World\Life;
use App\Requests\Person\PersonAddEventRequest;

class PersonEventAction
{
    public function __invoke(PersonAddEventRequest $request, int $id)
    {
        $back = fn (string $field, string $msg) => redirect(route('web.person.details-life', ['life_id' => $id, 'person_id' => Life::whereId($id)->first()?->person_id]))
            ->withErrors([$field => [$msg]])
            ->withInput($request->toArray());

        $life = Life::whereId($id)
            ->where('begin', '<=', $request->end)
            ->where('end', '>=', $request->begin)
            ->first();

        $eventType = EventType::whereId($request->type)->first();

        // validations

        if (!$life) {
            return $back('begin', 'Wrong event time');
        }
        if (!$eventType) {
            return $back('type', 'Wrong event type');
        }
        if ($eventType->is_work && !$request->work) {
            return $back('work', 'No work provided');
        }

        $model = new PersonEvent();
        $model->life_id = $id;
        $model->person_id = $life->person_id;
        $model->type_id = $request->type;
        $model->begin = $request->begin;
        $model->end = $request->end;
        if ($request->work) { $model->work_id = $request->work; }
        if ($request->strong) { $model->work_id = $request->strong; }
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
