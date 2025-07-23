<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\EventType;
use App\Requests\World\EventTypeAddRequest;

class EventTypeAddAction
{
    public function __invoke(EventTypeAddRequest $request)
    {
        $model = new EventType();
        $model->name = $request->name;
        $model->is_honor = !!$request->is_honor;
        $model->is_relation = !!$request->is_relation;
        $model->is_work = !!$request->is_work;
        $model->is_slave = !!$request->is_slave;
        $model->save();

        return redirect(route('web.planet.params'));
    }
}
