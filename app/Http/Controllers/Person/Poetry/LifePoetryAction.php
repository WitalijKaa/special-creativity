<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\Poetry\PoetryWord;
use App\Models\World\Life;
use Illuminate\Support\Collection;

class LifePoetryAction
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

        $wordsSlavic = PoetryWord::byLang(LL_RUS);
        $wordsEnglish = PoetryWord::byLang(LL_ENG);

        return $this->view($poetry, $llmVariants, $life, $wordsSlavic, $wordsEnglish);
    }

    protected function view($poetry, $llmVariants, $life, $wordsSlavic, $wordsEnglish)
    {
        return view('person.poetry.life-poetry', compact('poetry', 'llmVariants', 'life', 'wordsSlavic', 'wordsEnglish'));
    }
}
