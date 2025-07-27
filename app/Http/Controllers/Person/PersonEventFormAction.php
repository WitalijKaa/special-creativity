<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\PersonEvent;
use App\Requests\Person\PersonEditEventRequest;

class PersonEventFormAction
{
    public function __invoke(int $id)
    {
        $model = PersonEvent::whereId($id)->first();

        return view('planet.work-event-edit', compact('model'));
    }
}
