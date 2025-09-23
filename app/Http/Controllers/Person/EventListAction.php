<?php

namespace App\Http\Controllers\Person;

use App\Models\Collection\PersonEventCollection;
use Illuminate\Foundation\Http\FormRequest;

class EventListAction
{
    private const int YEARS_RANGE = 200;

    public function __invoke(FormRequest $request)
    {
        $year = (int)$request->get('year', 0);

        $models = PersonEventCollection::byYearsRange($year - self::YEARS_RANGE, $year + self::YEARS_RANGE);

        return view('person.events-list', compact('models', 'year'));
    }
}
