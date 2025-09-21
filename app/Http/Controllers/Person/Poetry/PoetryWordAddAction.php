<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\PoetryWord;
use App\Requests\Poetry\PoetryWordAddRequest;
use Illuminate\Http\RedirectResponse;

class PoetryWordAddAction
{
    public function __invoke(PoetryWordAddRequest $request): RedirectResponse
    {
        PoetryWord::create([
            'word' => trim($request->word),
            'definition' => trim($request->definition),
            'lang' => $request->lang,
        ]);

        return redirect()->route('web.person.poetry-words');
    }
}
