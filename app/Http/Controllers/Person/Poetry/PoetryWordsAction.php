<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;

class PoetryWordsAction
{
    public function __invoke()
    {
        $models = PoetryWord::whereLang(LL_RUS)->orderBy('word')->get();

        return view('person.poetry.words', compact('models'));
    }
}
