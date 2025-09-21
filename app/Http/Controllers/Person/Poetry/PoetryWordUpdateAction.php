<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Requests\Poetry\PoetryWordUpdateRequest;
use Illuminate\Http\RedirectResponse;

class PoetryWordUpdateAction
{
    public function __invoke(PoetryWordUpdateRequest $request, int $id): RedirectResponse
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
