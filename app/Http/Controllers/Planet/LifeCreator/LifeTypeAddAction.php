<?php

namespace App\Http\Controllers\Planet\LifeCreator;

use App\Models\World\LifeType;
use App\Requests\World\LifeTypeAddRequest;

class LifeTypeAddAction
{
    public function __invoke(LifeTypeAddRequest $request)
    {
        $model = new LifeType();
        $model->name = $request->life;
        $model->save();

        return redirect(route('web.planet.params'));
    }
}
