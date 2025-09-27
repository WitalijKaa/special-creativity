<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\World\Life;

class ParagraphsDeleteAction
{
    public function __invoke(int $life_id, string $lang, string $llm)
    {
        $llm = 'null' == $llm ? null : $llm;
        Poetry::whereLifeId($life_id)->whereLang($lang)->whereAi($llm)->delete();

        if (!$life = Life::whereId($life_id)->first()) {
            return redirect(route('web.person.list'));
        }

        return redirect()->route('web.person.poetry-life', ['life_id' => $life->id]);
    }
}
