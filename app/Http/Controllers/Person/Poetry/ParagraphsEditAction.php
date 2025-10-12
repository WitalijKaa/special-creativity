<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\World\Life;
use Illuminate\Database\Eloquent\Collection;

class ParagraphsEditAction
{
    public function __invoke(int $life_id, string $lang, string $llm)
    {
        $llm = 'null' == $llm ? null : $llm;

        if (!$life = Life::whereId($life_id)->with(['person', 'poetry'])->first()) {
            return redirect(route('web.person.list'));
        }
        $poetry = $life->poetrySpecific($lang, $llm);

        $llmVariants = new Collection();
        if (str_contains($llm, MASTER)) {
            $llmVariants = LifePoetryAction::searchLlmVariants($life, fn (string $llmName, string $lang) => LL_RUS == $lang && str_contains($llmName, FINAL_LLM));
            $llmVariants->unshift($life->poetry);
        }

        return view('person.poetry.poetry-edit', compact('poetry', 'life', 'llmVariants'));
    }
}
