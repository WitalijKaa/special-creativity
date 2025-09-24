<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\World\ForceEvent;
use App\Requests\Person\PersonAddRequest;

class PersonAddAction
{
    public function __invoke(PersonAddRequest $request, int $author_id)
    {
        $back = fn (string $field, string $msg) => redirect(route('web.person.details', ['id' => $author_id]))
            ->withErrors([$field => [$msg]])
            ->withInput($request->toArray());

        $author = Person::whereId($author_id)->first();
        $lastAuthorLife = $author->lives->last();
        /** @var \App\Models\World\Life $lastAuthorLife */

        // validation

        if (!ForceEvent::canHeCreatePerson($author)) {
            return $back('begin', 'Not enough Force amount');
        }
        if (!$lastAuthorLife->is_allods) {
            return $back('begin', 'May create only during Allods life');
        }
        if ($request->begin < $lastAuthorLife->begin || $request->begin > $lastAuthorLife->end) {
            return $back('begin', 'Should create new Life during last Life');
        }

        // save

        $model = new Person();
        $model->name = $request->name;
        $model->nick = $request->nick;
        $model->person_author_id = $author_id;
        $model->type = $author->type;
        $model->begin = $request->begin;
        $model->save();

        ForceEvent::createPerson($author, $request->begin)->andSave();

        return redirect(route('web.person.details', ['id' => $author_id]));
    }
}
