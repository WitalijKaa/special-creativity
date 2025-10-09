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
                    ->each(function (string $llm) use ($life, $llmVariants, $lang) {
                        if ($this->filterLlmVariants($llm)) {
                            $llmVariants->push($life->poetrySpecific($lang, $llm)); }
                        }
                    );
            });

        $wordsSlavic = PoetryWord::byLang(LL_RUS);
        $wordsEnglish = PoetryWord::byLang(LL_ENG);

        $llmAllNames = Poetry::whereLifeId($life->id)
            ->whereNotNull('llm')
            ->distinct('llm')
            ->pluck('llm');

        return $this->view($poetry, $llmVariants, $life, $wordsSlavic, $wordsEnglish, $llmAllNames);
    }

    protected function view($poetry, $llmVariants, $life, $wordsSlavic, $wordsEnglish, $llmAllNames = [])
    {
        return view('person.poetry.life-poetry', compact('poetry', 'llmVariants', 'life', 'wordsSlavic', 'wordsEnglish', 'llmAllNames'));
    }

    protected function filterLlmVariants(string $llmName)
    {
        return true;
    }
}
