<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Models\World\Life;

class LifeMasterPoetryAction
{
    public function __invoke(int $life_id)
    {
        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = $life->master_poetry;

        $wordsSlavic = PoetryWord::byLang(LL_RUS);
        $wordsEnglish = PoetryWord::byLang(LL_ENG);

        return view('person.poetry.life-master-poetry', compact('poetry', 'life', 'wordsSlavic', 'wordsEnglish'));
    }
}
