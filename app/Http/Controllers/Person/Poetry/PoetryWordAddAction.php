<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Requests\Poetry\PoetryWordAddRequest;

class PoetryWordAddAction
{
    public function __invoke(PoetryWordAddRequest $request)
    {
        PoetryWord::create([
            'word' => trim($request->word),
            'definition' => trim($request->definition),
            'lang' => $request->lang,
        ]);

        return redirect()->route('web.person.poetry-words');
    }
}
