<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Poetry;
use App\Requests\Poetry\ChapterToLlmRequest;
use App\Requests\Poetry\ChapterTranslateRequest;

class LifePoetryTranslateAction
{
    public function __invoke(int $life_id)
    {
        $firstStage = explode('_', config('basic.final_flow.alpha'))[0];

        if (Poetry::whereLifeId($life_id)->whereLlm($firstStage)->whereLang(LL_ENG)->exists()) {
            Poetry::whereLifeId($life_id)
                ->whereLlm($firstStage)
                ->whereLang(LL_ENG)
                ->update(['llm' => V_TRANSLATION]);

            return redirect()->route('web.person.poetry-life', ['life_id' => $life_id])
                ->with(APP_MSG, 'U do have translation!');
        }

        $config = LlmConfig::configByExplode(explode('.', $firstStage));
        $request = (new ChapterTranslateRequest())->replace(array_merge(ChapterToLlmRequest::paramsByConfig($config), [
            'to_lang' => LL_ENG,
            'from_lang' => LL_RUS,
            'from_llm' => null,
        ]));
        (new ChapterTranslateAction())($life_id, $request);

        Poetry::whereLifeId($life_id)
            ->whereLlm(V_TRANSLATION)
            ->whereLang(LL_ENG)
            ->delete();
        Poetry::whereLifeId($life_id)
            ->whereLlm($firstStage)
            ->whereLang(LL_ENG)
            ->update(['llm' => V_TRANSLATION]);

        return redirect()->route('web.person.poetry-life', ['life_id' => $life_id]);
    }
}
