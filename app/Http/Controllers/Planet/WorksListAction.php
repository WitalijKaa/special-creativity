<?php

namespace App\Http\Controllers\Planet;

use App\Models\World\Work;
use Illuminate\Foundation\Http\FormRequest;

class WorksListAction
{
    public function __invoke(FormRequest $request)
    {
        $models = Work::orderBy('id')
            ->with(['events.connections.life.person', 'events.life.person'])
            ->get()
            ->each(fn (Work $model) => $model->calculate());

        return view('planet.works-list', compact('models'));
    }
}
