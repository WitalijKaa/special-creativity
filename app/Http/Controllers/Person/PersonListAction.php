<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class PersonListAction
{
    public function __invoke(FormRequest $request)
    {
        $sort = $request->get('sort', 'id');
        $desc = str_starts_with($sort, 'desc_');
        $sort = $desc ? substr($sort, strlen('desc_')) : $sort;
        $year = $request->get('year', 0);

        $models = !$year ? Person::all() : Person::where('begin', '<', $year)->get();
        $models = $desc ? $models->sortByDesc($sort) : $models->sortBy($sort);

        return view('person.persons-list', compact('models', 'year'));
    }
}
