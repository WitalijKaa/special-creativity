<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use Illuminate\Contracts\View\View;

class PoetryWordEditAction
{
    public function __invoke(int $id): View
    {
        $model = PoetryWord::query()->findOrFail($id);

        return view('person.poetry.word-edit', compact('model'))
            ->with('status', "Word $model->word edited");
    }
}
