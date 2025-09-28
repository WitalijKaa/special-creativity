<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\AiRequest\TranslateWithLlm;
use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ChapterTranslateRequest;

class ChapterTranslateAction
{
    public function __invoke(int $life_id, ChapterTranslateRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = !$request->from_llm ? $life->poetry : $life->poetrySpecific($request->from_lang, $request->from_llm);

        $config = new LlmConfig($request->llm);
        $config->applyPipeParam($request->llm_mode);
        $config->applyPipeParam($request->llm_quality);
        $config->applyPipeParam($request->llm_rise_creativity);

        $translate = new TranslateWithLlm();
        $translate->useConfig($config);
        $response = $translate->translatePoetryMass($poetry, $request->to_lang);

        $nextLlm = !$request->from_llm ? $response->first()->llm : explode('.', $request->from_llm)[0] . '_' . $response->first()->llm;

        Poetry::whereLifeId($life->id)->whereLlm($nextLlm)->whereLang($request->to_lang)->delete();
        foreach ($response as $model) {
            $model->llm = $nextLlm;
            $model->save();
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }
}
