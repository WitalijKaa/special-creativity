<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Poetry;
use App\Requests\Poetry\ChapterToLlmRequest;
use App\Requests\Poetry\ChapterTranslateRequest;

class LifePoetryVersionsAction
{
    public function __invoke(int $life_id, string $specific = 'all')
    {
        $versions = config('basic.final_flow');
        if (array_key_exists($specific, $versions)) {
            $versions = [$specific => $versions[$specific]];
        }

        foreach ($versions as $specific => $stages) {
            $fromLlm = V_TRANSLATION;
            $toLlm = explode('_', $stages)[1];

            $config = LlmConfig::configByExplode(explode('.', $toLlm));
            $request = (new ChapterToLlmRequest())->replace(array_merge(ChapterToLlmRequest::paramsByConfig($config), [
                'from_llm' => $fromLlm,
            ]));
            (new ChapterImproveAction())($life_id, $request);

            Poetry::whereLifeId($life_id)
                ->whereLlm($specific)
                ->whereLang(LL_ENG)
                ->delete();
            Poetry::whereLifeId($life_id)
                ->whereLlm(V_TRANSLATION . '_' . $toLlm)
                ->whereLang(LL_ENG)
                ->update(['llm' => $specific]);
        }

        return redirect()->route('web.person.poetry-life', ['life_id' => $life_id]);
    }
}
