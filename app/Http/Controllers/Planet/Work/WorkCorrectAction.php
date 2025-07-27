<?php

namespace App\Http\Controllers\Planet\Work;

use App\Models\Work\Work;
use App\Requests\World\WorkEditRequest;

class WorkCorrectAction
{
    public function __invoke(int $id)
    {
        $model = Work::whereId($id)->first();
        $model->calculate();
        $model->begin = $model->calculations->begin;
        $model->end = $model->calculations->end;
        $model->save();

        return redirect()->back();
    }
}
