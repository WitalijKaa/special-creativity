<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\World\Life;

class ParagraphsEditAction
{
    public function __invoke(int $life_id, string $lang, string $llm)
    {
        $llm = 'null' == $llm ? null : $llm;

        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }
        $poetry = $life->poetrySpecific($lang, $llm);

        return view('person.poetry.poetry-edit', compact('poetry', 'life'));
    }
}
