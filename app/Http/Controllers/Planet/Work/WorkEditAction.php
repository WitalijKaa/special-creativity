<?php

namespace App\Http\Controllers\Planet\Work;

use App\Models\Work\Work;
use App\Requests\World\WorkEditRequest;

class WorkEditAction
{
    public function __invoke(WorkEditRequest $request, int $id)
    {
        $model = Work::whereId($id)->first();
        $model->name = $request->name;
        $model->capacity = $request->capacity ?: null;
        $model->consumers = $request->consumers ?: null;
        $model->save();

        return redirect()->back();
    }
}
