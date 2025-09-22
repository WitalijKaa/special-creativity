<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Requests\Poetry\PoetryWordRequest;

class PoetryWordChangeAction
{
    public function __invoke(PoetryWordRequest $request, int $id)
    {
        $model = PoetryWord::query()->findOrFail($id);
        $model->word = trim($request->word);
        $model->word_eng = trim($request->word_eng);
        $model->word_ai = $request->word_ai ? trim($request->word_ai) : null;
        $model->definition = trim($request->definition);
        $model->lang = $request->lang;
        $model->save();

        return redirect()->route('web.person.poetry-words')
            ->with('status', "Word $model->word changed");
    }
}
