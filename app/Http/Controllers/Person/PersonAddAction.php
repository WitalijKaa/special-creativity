<?php

namespace App\Http\Controllers\Person;

use App\Models\Person\Person;
use App\Models\World\LifeType;
use App\Requests\Person\PersonAddRequest;

class PersonAddAction
{
    public function __invoke(PersonAddRequest $request, int $author_id)
    {
        $back = fn (string $field, string $msg) => redirect(route('web.person.details', ['id' => $author_id]))
            ->withErrors([$field => [$msg]])
            ->withInput($request->toArray());

        $author = Person::whereId($author_id)->first();
        $prevAuthorLife = $author->lives->last();
        /** @var \App\Models\World\Life $prevAuthorLife */

        if ($prevAuthorLife->type_id != LifeType::ALLODS) {
            return $back('begin', 'May create only during Allods life');
        }
        if ($request->begin < $prevAuthorLife->begin || $request->begin > $prevAuthorLife->end) {
            return $back('begin', 'Should create new Life during last Life');
        }

        $model = new Person();
        $model->name = $request->name;
        $model->person_author_id = $author_id;
        $model->type_id = $author->type_id;
        $model->begin = $request->begin;
        $model->save();

        $author->force_person -= 95;
        $author->save();

        return redirect(route('web.person.details', ['id' => $author_id]));
    }
}
