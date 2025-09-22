<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\World\Life;

class ParagraphsDeleteAction
{
    public function __invoke(int $life_id, string $lang, string $llm)
    {
        $llm = 'null' == $llm ? null : $llm;

        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }
        Poetry::whereLifeId($life->id)->whereLang($lang)->whereAi($llm)->delete();

        return redirect()->route('web.person.poetry-life', ['life_id' => $life->id]);
    }
}
