<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;

class PoetryWordDeleteAction
{
    public function __invoke(int $id)
    {
        PoetryWord::whereId($id)->delete();

        return redirect()->route('web.person.poetry-words');
    }
}
