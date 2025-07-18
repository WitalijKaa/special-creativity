<?php

namespace App\Http\Controllers\Person;

class PersonFormAction
{
    public function __invoke()
    {
        return view('person.add');
    }
}
