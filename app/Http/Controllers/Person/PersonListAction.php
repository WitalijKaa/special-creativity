<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use Illuminate\Http\Request;

class PersonListAction
{
    public function __invoke(Request $request)
    {
        $sort = $request->get('sort', 'id');
        $desc = str_starts_with($sort, 'desc_');
        $sort = $desc ? substr($sort, strlen('desc_')) : $sort;

        $models = Person::all();
        $models = $desc ? $models->sortByDesc($sort) : $models->sortBy($sort);

        return view('person.list', compact('models'));
    }
}
