<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Poetry;
use App\Requests\Poetry\ChapterToLlmRequest;
use App\Requests\Poetry\ChapterTranslateRequest;

class LifePoetryTranslateAgainAction
{
    public function __invoke(int $life_id, string $specific = 'all')
    {
        $versions = config('basic.final_flow');
        if (array_key_exists($specific, $versions)) {
            $versions = [$specific => $versions[$specific]];
        }

        foreach ($versions as $specific => $stages) {
            $fromLlm = $specific;
            $toLlm = explode('_', $stages)[2];

            $config = LlmConfig::configByExplode(explode('.', $toLlm));
            $request = (new ChapterTranslateRequest())->replace(array_merge(ChapterToLlmRequest::paramsByConfig($config), [
                'to_lang' => LL_RUS,
                'from_lang' => LL_ENG,
                'from_llm' => $fromLlm,
            ]));
            (new ChapterTranslateAction())($life_id, $request);

            Poetry::whereLifeId($life_id)
                ->whereLlm($specific)
                ->whereLang(LL_RUS)
                ->delete();
            Poetry::whereLifeId($life_id)
                ->whereLlm($specific . '_' . $toLlm)
                ->whereLang(LL_RUS)
                ->update(['llm' => $specific]);
        }

        return redirect()->route('web.person.poetry-life', ['life_id' => $life_id]);
    }
}
