<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Requests\Poetry\PoetryWordChangeRequest;

class PoetryWordChangeAction
{
    public function __invoke(PoetryWordChangeRequest $request, int $id)
    {
        $model = PoetryWord::query()->findOrFail($id);
        $model->word = trim($request->word);
        $model->definition = trim($request->definition);
        $model->lang = $request->lang;
        $model->save();

        return redirect()->route('web.person.poetry-words')
            ->with('status', "Word $model->word changed");
    }
}
