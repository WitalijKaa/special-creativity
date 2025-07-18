<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;

class PersonDetailsAction
{
    public function __invoke(int $id)
    {
        if (!$model = Person::whereId($id)->with(['lives.type'])->first()) {
            return redirect(route('web.person.list'));
        }

        return view('person.details', compact('model'));
    }
}
