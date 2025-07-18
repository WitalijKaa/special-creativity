<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Requests\Person\PersonAddRequest;

class PersonAddAction
{
    public function __invoke(PersonAddRequest $request)
    {
        $model = new Person();
        $model->name = $request->name;
        $model->save();

        return redirect(route('web.person.list'));
    }
}
