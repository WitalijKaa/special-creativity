<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Collection\PersonEventCollection;
use App\Models\World\Life;

class LifePoetryAction
{
    public function __invoke(int $life_id)
    {
        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = $life->poetry;

        $events = PersonEventCollection::byLifeID($life->id)
            ->sortNice()
            ->addSyntheticBirthDeath($life);

        return view('person.poetry.life-poetry', compact('poetry', 'life', 'events'));
    }
}
