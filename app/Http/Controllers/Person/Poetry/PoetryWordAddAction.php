<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Requests\Poetry\PoetryWordRequest;

class PoetryWordAddAction
{
    public function __invoke(PoetryWordRequest $request)
    {
        PoetryWord::create([
            'word' => trim($request->word),
            'word_eng' => trim($request->word_eng),
            'word_ai' => $request->word_ai ? trim($request->word_ai) : null,
            'definition' => trim($request->definition),
            'lang' => $request->lang,
        ]);

        return redirect()->route('web.person.poetry-words');
    }
}
