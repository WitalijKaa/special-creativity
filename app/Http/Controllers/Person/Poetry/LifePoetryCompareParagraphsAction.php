<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\Poetry\PoetryWord;
use App\Models\World\Life;

class LifePoetryCompareParagraphsAction
{
    public function __invoke(int $life_id)
    {
        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = $life->poetry;

        $llmVariants = Poetry::whereLifeId($life->id)
            ->whereNotNull('ai')
            ->select('ai')
            ->distinct()
            ->get()
            ->pluck('ai')
            ->map(fn (string $llm) => $life->poetrySpecific(LL_ENG, $llm));

        $words = PoetryWord::byLang(LL_RUS);

        return view('person.poetry.life-poetry-compare-paragraphs', compact('poetry', 'llmVariants', 'life', 'words'));
    }
}
