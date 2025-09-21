<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Collection\PersonEventCollection;
use App\Models\Poetry\Poetry;
use App\Models\Poetry\PoetryWord;
use App\Models\World\Life;

class LifePoetryAction
{
    public function __invoke(int $life_id)
    {
        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = $life->poetry;

        $events = PersonEventCollection::byLifeID($life->id)
            ->sortNice()
            ->addSyntheticBirthDeath($life);

        $aiVariants = Poetry::whereLifeId($life->id)
            ->whereNotNull('ai')
            ->select('ai')
            ->distinct()
            ->get()
            ->pluck('ai')
            ->map(fn (string $llm) => $life->poetrySpecific(LL_ENG, $llm));

        $words = PoetryWord::byLang(LL_RUS);

        return view('person.poetry.life-poetry', compact('poetry', 'aiVariants', 'life', 'events', 'words'));
    }
}
