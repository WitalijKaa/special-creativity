<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\Person\PersonEventSynthetic;
use App\Models\World\Life;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class PersonDetailsAction
{
    public function __invoke(FormRequest $request, int $id)
    {
        $year = $request->get('year', 0);

        if (!$model = Person::whereId($id)->with(['lives'])->first()) {
            return redirect(route('web.person.list'));
        }

        $eventsYear = $year > 0 ? $year : $model->last_year + 42;

        $events = $year < 1 ?
            PersonEvent::wherePersonId($id)
                ->orWhereIn('id', PersonEventConnect::wherePersonId($id)->pluck('event_id')->unique())
                ->with(['connections.life', 'type', 'person'])
                ->limit(42)
                ->get()
            :
            PersonEvent::where(fn (Builder $orBuilder) => $orBuilder
                    ->where('person_id', $id)
                    ->orWhereIn('id', PersonEventConnect::wherePersonId($id)->pluck('event_id')->unique())
                )
                ->where('begin', '<=', $year)
                ->where('begin', '>', $year - 200)
                ->with(['connections.life', 'type', 'person'])
                ->get();

        $eventsFuture = $year < 1 ? null : PersonEvent::where(fn (Builder $orBuilder) => $orBuilder
                ->where('person_id', $id)
                ->orWhereIn('id', PersonEventConnect::wherePersonId($id)->pluck('event_id')->unique())
            )
            ->where('begin', '>', $year)
            ->where('begin', '<', $year + 200)
            ->with(['connections.life', 'type', 'person'])
            ->get();

        foreach (Life::wherePersonId($id)->whereType(Life::ALLODS)->where('begin', '<=', $eventsYear)->get() as $allodsLife) {
            $events->push($allodsLife->synthetic(PersonEventSynthetic::ALLODS, $allodsLife->begin, $allodsLife->end));
        }
        $events = $events->sortBy('begin')->values();

        return view('person.details', compact('model', 'events', 'eventsFuture', 'year'));
    }
}
