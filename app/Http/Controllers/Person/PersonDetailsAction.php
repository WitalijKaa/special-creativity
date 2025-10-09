<?php

namespace App\Http\Controllers\Person;

use App\Models\Collection\PersonEventBuilder;
use App\Models\Collection\PersonEventCollection;
use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

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

        $poetryCount = DB::query()->selectRaw('tt.life_id, COUNT(tt.life_id) AS count')
            ->groupBy('tt.life_id')
            ->from(Poetry::wherePersonId($model->id)->groupBy(['life_id', 'llm'])->select(['life_id', 'llm']), 'tt')
            ->pluck('count', 'life_id');

        $model->lives->each(function (Life $life) use ($poetryCount) {
            $life->cachedPoetryCount = (int)$poetryCount->get($life->id, 0);
        });

        if ($year > 0) {
            $events = $this->eventsQueryToCollection(
                PersonEventBuilder::byYearsAndPersonID($id, fromYear: $year - self::LIMIT_YEARS, untilYear: $year),
                $id,
                fromYear: $year - self::LIMIT_YEARS,
                untilYear: $year
            );
            $eventsFuture = $this->eventsQueryToCollection(
                PersonEventBuilder::byYearsAndPersonID($id, fromYear: $year + 1, untilYear: $year + self::LIMIT_YEARS),
                $id,
                fromYear: $year + 1,
                untilYear: $year + self::LIMIT_YEARS
            );
        }
        else {
            $events = $this->eventsQueryToCollection(PersonEventBuilder::honorRelationsSlaveBy($id)->limit(self::LIMIT_EVENTS), $id);
            $eventsFuture = null;
        }

        return view('person.person-details', compact('model', 'events', 'eventsFuture', 'year'));
    }

    private function eventsQueryToCollection(Builder|PersonEvent $query, int $personID, ?int $fromYear = null, ?int $untilYear = null): ?PersonEventCollection
    {
        return PersonEventCollection::toCollection($query->with(['connections.life', 'type', 'person'])->get())
            ->addSyntheticBackToAllodsByPersonEvents($personID, $fromYear, $untilYear)
            ->addSyntheticCreationsByPersonEvents($personID, $fromYear, $untilYear)
            ->sortNice();
    }
}
