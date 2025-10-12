<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\AiRequest\FinalWithLlm;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\PoetryFinalRequest;
use Illuminate\Support\Collection;

class LifePoetryFinalAction
{
    private const FINAL_RANK = ['ok' => '', 'nice' => '_nice', 'mega' => '_gold'];

    public function __invoke(int $life_id, PoetryFinalRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }
        if (!$life->finalSlavicPoetry((bool)$request->emotions)) {
            return redirect(route('web.person.list'));
        }
        $poetryOriginal = $life->poetry;
        $poetryAlpha = $life->poetrySpecific(LL_RUS, config('basic.final_flow.alpha'));
        $poetryBeta = $life->poetrySpecific(LL_RUS, config('basic.final_flow.beta'));
        $poetryEmotional = $request->emotions ? $life->poetrySpecific(LL_RUS, config('basic.final_flow.emotion')) : new Collection();

        $config = $request->llmConfig();
        $finalName = explode('_', $request->llm);
        $finalLlm = 'final_' . end($finalName) . self::FINAL_RANK[$request->llm_quality] . ($request->emotions ? '_emo' : '');

        $previous = Poetry::whereLifeId($life->id)
            ->whereLlm($finalLlm)
            ->whereLang(LL_RUS)
            ->pluck('ix_text');

        foreach ($poetryOriginal as $ix => $paragraph) {
            if ($previous->count() < $poetryOriginal->count() && $previous->contains($paragraph->ix_text)) {
                continue;
            }

            $finalize = new FinalWithLlm();
            $finalize->useConfig($config);
            $finalParagraph = $finalize->combineParts(
                $poetryOriginal->get($ix),
                $poetryAlpha->get($ix),
                $poetryBeta->get($ix),
                $request->emotions ? $poetryEmotional->get($ix) : null,
            );
            $finalModel = $paragraph->llmModification($finalParagraph, LL_RUS, $finalLlm);
            Poetry::whereLifeId($life->id)
                ->whereLlm($finalLlm)
                ->whereLang(LL_RUS)
                ->whereIxText($finalModel->ix_text)
                ->delete();
            $finalModel->save();
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }
}
