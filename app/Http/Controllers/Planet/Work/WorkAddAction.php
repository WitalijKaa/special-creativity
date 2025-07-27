<?php

namespace App\Http\Controllers\Planet\Work;

use App\Models\Work\Work;
use App\Requests\World\WorkAddRequest;

class WorkAddAction
{
    public function __invoke(WorkAddRequest $request)
    {
        $model = new Work();
        $model->name = $request->name;
        $model->begin = $request->begin;
        $model->end = $request->end;
        $model->capacity = $request->capacity ?: null;
        $model->save();

        return redirect()->back();
    }
}
