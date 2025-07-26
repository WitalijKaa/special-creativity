<?php

namespace App\Http\Controllers\Person;

use App\Models\Collection\PersonEventBuilder;
use App\Models\Collection\PersonEventCollection;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;

class PersonDetailsAction
{
    private const int LIMIT_EVENTS = 42;
    private const int LIMIT_YEARS = 200;

    public function __invoke(FormRequest $request, int $id)
    {
        $year = $request->get('year', 0);

        if (!$model = Person::whereId($id)->with(['lives'])->first()) {
            return redirect(route('web.person.list'));
        }

        if ($year > 0) {
            $events = $this->eventsQueryToCollection(PersonEventBuilder::byYearsAndPersonID($id, $year - self::LIMIT_YEARS, $year), $id, untilYear: $year);
            $eventsFuture = $this->eventsQueryToCollection(PersonEventBuilder::byYearsAndPersonID($id, $year + 1, $year + self::LIMIT_YEARS), $id, fromYear: $year + 1, untilYear: $year + self::LIMIT_YEARS);
        }
        else {
            $events = $this->eventsQueryToCollection(PersonEventBuilder::honorRelationsSlaveBy($id)->limit(self::LIMIT_EVENTS), $id);
            $eventsFuture = null;
        }

        return view('person.details', compact('model', 'events', 'eventsFuture', 'year'));
    }

    private function eventsQueryToCollection(Builder|PersonEvent $query, int $personID, ?int $fromYear = null, ?int $untilYear = null): ?PersonEventCollection
    {
        return PersonEventCollection::toCollection($query->with(['connections.life', 'type', 'person'])->get())
            ->addSyntheticBackToAllodsEvents($personID, $fromYear, $untilYear)
            ->sortNice();
    }
}
