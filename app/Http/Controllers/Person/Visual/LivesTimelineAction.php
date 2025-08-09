<?php

namespace App\Http\Controllers\Person\Visual;

use App\Models\Collection\LifeCollection;
use App\Models\Person\Person;
use Illuminate\Foundation\Http\FormRequest;

class LivesTimelineAction
{
    public function __invoke(FormRequest $request)
    {
        $begin = (int)$request->get('begin', 0);
        $end = (int)$request->get('end', 1000);
        $end = $end > $begin ? $end : $begin + 1;

        $personsIDs = LifeCollection::planetByRange($begin, $end)->pluck('person_id')->unique()->toArray();
        $models = Person::whereIn('id', $personsIDs)->with(['lives'])->get();

        return view('person.visual.lives-timeline', compact('models', 'begin', 'end'));
    }
}
