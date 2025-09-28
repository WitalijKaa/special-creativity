<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\Poetry\PoetryWord;
use App\Models\World\Life;
use Illuminate\Support\Collection;

class LifePoetryCompareParagraphsAction
{
    public function __invoke(int $life_id)
    {
        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = $life->poetry;

        $llmVariants = new Collection();
        Poetry::whereLifeId($life->id)
            ->whereNotNull('llm')
            ->select('lang')
            ->distinct()
            ->get()
            ->pluck('lang')
            ->each(function (string $lang) use ($llmVariants, $life) {
                Poetry::whereLifeId($life->id)
                    ->whereNotNull('llm')
                    ->whereLang($lang)
                    ->select('llm')
                    ->distinct()
                    ->get()
                    ->pluck('llm')
                    ->each(fn (string $llm) => $llmVariants->push($life->poetrySpecific($lang, $llm)));
            });

        $words = PoetryWord::byLang(LL_RUS);

        return view('person.poetry.life-poetry-compare-paragraphs', compact('poetry', 'llmVariants', 'life', 'words'));
    }
}
