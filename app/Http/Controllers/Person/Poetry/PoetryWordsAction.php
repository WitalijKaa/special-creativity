<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use Illuminate\Contracts\View\View;

class PoetryWordsAction
{
    public function __invoke(): View
    {
        $words = PoetryWord::whereLang(LL_RUS)->get();

        return view('poetry.words', compact('words'));
    }
}
