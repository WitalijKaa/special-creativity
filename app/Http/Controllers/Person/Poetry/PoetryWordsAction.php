<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;

class PoetryWordsAction
{
    public function __invoke()
    {
        $slavic = PoetryWord::whereLang(LL_RUS)->orderBy('word')->get();
        $english = PoetryWord::whereLang(LL_ENG)->orderBy('word')->get();

        return view('person.poetry.words', compact('slavic', 'english'));
    }
}
