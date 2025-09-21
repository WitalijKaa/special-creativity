<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use Illuminate\Http\RedirectResponse;

class PoetryWordDeleteAction
{
    public function __invoke(int $id): RedirectResponse
    {
        PoetryWord::whereId($id)->delete();

        return redirect()->route('web.person.poetry-words');
    }
}
