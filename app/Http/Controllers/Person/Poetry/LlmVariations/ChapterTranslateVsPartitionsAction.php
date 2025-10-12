<?php

namespace App\Http\Controllers\Person\Poetry\LlmVariations;

use App\Models\AiRequest\TranslateWithLlm;
use App\Models\Collection\PoetryCollection;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ChapterTranslateRequest;

class ChapterTranslateVsPartitionsAction
{
    public function __invoke(int $life_id, ChapterTranslateRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $poetry = !$request->from_llm ? $life->poetry : $life->poetrySpecific($request->from_lang, $request->from_llm);
        $poetryPartitions = PoetryCollection::toCollection($poetry);

        $config = $request->llmConfig();
        $config->subMode = 'partition';
        $nextLlm = null;
        foreach ($poetryPartitions->partitions() as $partition) {
            $translate = new TranslateWithLlm();
            $translate->useConfig($config);
            $response = $translate->translateChapter($partition, $request->to_lang);

            if (!$nextLlm) {
                $nextLlm = ($request->from_llm ? $request->from_llm . '_' : '') . $response->first()->llm;
                $nextLlm .= '.' . $request->to_lang;
                Poetry::whereLifeId($life->id)->whereLlm($nextLlm)->whereLang($request->to_lang)->delete();
            }

            foreach ($response as $model) {
                $model->llm = $nextLlm;
                $model->save();
            }
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }
}
