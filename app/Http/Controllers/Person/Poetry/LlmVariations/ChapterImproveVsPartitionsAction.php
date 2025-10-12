<?php

namespace App\Http\Controllers\Person\Poetry\LlmVariations;

use App\Models\AiRequest\ImproveWithLlm;
use App\Models\Collection\PoetryCollection;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ChapterToLlmRequest;

class ChapterImproveVsPartitionsAction
{
    public function __invoke(int $life_id, ChapterToLlmRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }
        $poetry = $life->poetrySpecific(LL_ENG, $request->from_llm);
        $poetryPartitions = PoetryCollection::toCollection($poetry);

        $config = $request->llmConfig();
        $config->subMode = 'partition';
        $nextLlm = null;
        foreach ($poetryPartitions->partitions() as $partition) {
            $improve = new ImproveWithLlm();
            $improve->useConfig($config);
            $response = $improve->improveChapter($partition);

            if (!$nextLlm) {
                $nextLlm = ($request->from_llm ? $request->from_llm . '_' : '') . $response->first()->llm;
                $nextLlm .= '.improve';
                Poetry::whereLifeId($life->id)->whereLlm($nextLlm)->whereLang(LL_ENG)->delete();
            }

            foreach ($response as $model) {
                $model->llm = $nextLlm;
                $model->save();
            }
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }
}
