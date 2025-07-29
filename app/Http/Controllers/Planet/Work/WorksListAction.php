<?php

namespace App\Http\Controllers\Planet\Work;

use App\Models\Collection\AbstractBuilder;
use App\Models\Work\Work;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class WorksListAction
{
    private const int YEARS_RANGE = 50;
    private const int HUGE_WORK_LENGTH = 500;

    public function __invoke(FormRequest $request)
    {
        $year = $request->get('year', 0);

        $query = Work::orderBy('id')->with(['events.connections.life.person', 'events.life.person']);
        if ($year > 0) {
            AbstractBuilder::whereBeginEndInRange($query, $year - self::YEARS_RANGE, $year + self::YEARS_RANGE);
        } else {
            $query->where(DB::raw('`end` - `begin`'), '>', self::HUGE_WORK_LENGTH)
                  ->orWhereIn('id', Work::orderByDesc('id')->limit(13)->pluck('id'));
        }

        $models = $query->get()->each(fn (Work $model) => $model->calculate());

        return view('planet.works-list', compact('models', 'year'));
    }
}
