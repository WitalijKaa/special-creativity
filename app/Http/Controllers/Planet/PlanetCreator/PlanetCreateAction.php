<?php

namespace App\Http\Controllers\Planet\PlanetCreator;

use App\Models\Person\Person;
use App\Models\World\Planet;
use App\Requests\World\StarSystemCreateRequest;

class PlanetCreateAction
{
    public function __invoke(StarSystemCreateRequest $request)
    {
        $planet = new Planet();
        $planet->name = $request->name;
        $planet->days = $request->days;
        $planet->hours = $request->hours;
        $planet->force = $request->force;
        $planet->force_create = $request->force_create;
        $planet->force_man_up = $request->force_man_up;
        $planet->force_woman_up = $request->force_woman_up;
        $planet->force_woman_special_up = $request->force_woman_special_up;
        $planet->force_woman_man_allowed = $request->force_woman_man_allowed;
        $planet->force_man_first_up = $request->force_man_first_up;
        $planet->force_woman_first_up = $request->force_woman_first_up;
        $planet->force_man_min = $request->force_man_min;
        $planet->force_woman_min = $request->force_woman_min;
        $planet->save();

        $person = new Person();
        $person->id = Person::ORIGINAL;
        $person->name = $request->person;
        $person->nick = $request->nick;
        $person->type = Person::IMPERIUM;
        $person->begin = 0;
        $person->force_person = $request->force;
        $person->save();

        return redirect(route('web.basic.space'));
    }
}
