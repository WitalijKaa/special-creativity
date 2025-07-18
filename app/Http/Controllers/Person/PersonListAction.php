<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;

class PersonListAction
{
    public function __invoke()
    {
        $models = Person::orderBy('id')->get();

        return view('person.list', compact('models'));
    }
}
