<?php

namespace App\Http\Controllers\Planet\Work;

use App\Models\Collection\AbstractBuilder;
use App\Models\Work\Work;
use Illuminate\Foundation\Http\FormRequest;

class WorksListAction
{
    private const int YEARS_RANGE = 50;

    public function __invoke(FormRequest $request)
    {
        $year = $request->get('year', 0);

        $query = Work::orderBy('id')->with(['events.connections.life.person', 'events.life.person']);
        if ($year > 0) {
            AbstractBuilder::whereBeginEndInRange($query, $year - self::YEARS_RANGE, $year + self::YEARS_RANGE);
        }

        $models = $query->get()->each(fn (Work $model) => $model->calculate());

        return view('planet.works-list', compact('models', 'year'));
    }
}
