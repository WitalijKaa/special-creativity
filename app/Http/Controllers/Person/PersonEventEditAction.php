<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\PersonEvent;
use App\Requests\Person\PersonEditEventRequest;

class PersonEventEditAction
{
    public function __invoke(PersonEditEventRequest $request, int $id)
    {
        $model = PersonEvent::whereId($id)->first();
        $model->begin = $request->begin;
        $model->end = $request->end;
        $model->strong = $request->strong;
        $model->comment = $request->comment;
        $model->save();

        return $model->work_id ? redirect(route('web.basic.works-details', ['id' => $model->work_id])) : redirect()->back();
    }
}
