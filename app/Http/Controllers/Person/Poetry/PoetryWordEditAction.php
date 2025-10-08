<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;

class PoetryWordEditAction
{
    public function __invoke(int $id)
    {
        $model = PoetryWord::query()->findOrFail($id);

        return view('person.poetry.word-edit', compact('model'));
    }
}
