<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventConnect;
use App\Models\Person\PersonEventSynthetic;
use App\Models\World\Life;
use App\Models\World\Work;
use Illuminate\Database\Eloquent\Builder;

class LifeDetailsAction
{
    public function __invoke(int $person_id, int $life_id)
    {
        if (!$model = Life::wherePersonId($person_id)->whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $connections = Life::where('person_id', '!=', $person_id)
            ->where(function (Builder $orBuilder) use ($model) {
                return $orBuilder->where(function (Builder $builder) use ($model) {
                    return $builder->where('begin', '<=', $model->begin)->where('end', '>=', $model->begin);

                })->orWhere(function (Builder $builder) use ($model) {
                    return $builder->where('begin', '<=', $model->end)->where('end', '>=', $model->end);
                });
            })
            ->whereType($model->type)
            ->get();

        $events = PersonEvent::whereLifeId($life_id)
            ->orWhereIn('id', PersonEventConnect::whereLifeId($life_id)->pluck('event_id')->unique())
            ->with(['connections.life', 'type', 'person'])
            ->orderBy('begin')
            ->get();
        if ($model->is_planet) {
            $events->unshift($model->synthetic(PersonEventSynthetic::BIRTH, $model->begin));
            $events->push($model->synthetic(PersonEventSynthetic::DEATH, $model->end));
        }

        $work = Work::where(function (Builder $orBuilder) use ($model) {
            return $orBuilder->where(function (Builder $builder) use ($model) {
                return $builder->where('begin', '<=', $model->begin)->where('end', '>=', $model->begin);

            })->orWhere(function (Builder $builder) use ($model) {
                return $builder->where('begin', '<=', $model->end)->where('end', '>=', $model->end);
            });
        })->get();

        return view('person.life-details', compact('model', 'connections', 'events', 'work'));
    }
}
