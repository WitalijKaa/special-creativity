<?php

namespace App\Http\Controllers\Planet\Work;

use App\Models\Work\Work;
use Illuminate\Foundation\Http\FormRequest;

class WorksDetailsAction
{
    public function __invoke(FormRequest $request, int $id)
    {
        $year = $request->get('year', 0);

        $model = Work::whereId($id)
            ->with(['events.connections.life.person', 'events.life.person'])
            ->first();

        $model->calculate();
        foreach ($model->events as $workEvent) {
            $workEvent->life->lifeWork;
            $workEvent->work->calculations = $model->calculations;
        }

        return view('planet.works-details', compact('model', 'year'));
    }
}
