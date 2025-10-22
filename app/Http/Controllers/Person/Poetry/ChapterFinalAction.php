<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\AiRequest\FinalWithLlm;
use App\Models\Poetry\Llm\LlmConfig;
use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\PoetryFinalRequest;
use Illuminate\Support\Collection;

class ChapterFinalAction
{
    private const array FINAL_RANK = ['ok' => '', 'nice' => '_nice', 'mega' => '_gold'];
    private const string MARK_SKIP = '!!';
    private const string MARK_SPLICE = '==';

    private LlmConfig $llmConfig;

    public function __invoke(int $life_id, PoetryFinalRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }
        if (!$life->finalSlavicPoetry((bool)$request->emotions)) {
            return redirect(route('web.person.list'));
        }
        $poetryOriginal = $life->poetry;
        $poetryAlpha = $life->poetrySpecific(LL_RUS, V_ALPHA);
        $poetryBeta = $life->poetrySpecific(LL_RUS, V_BETA);
        $poetryEmotional = $request->emotions ? $life->poetrySpecific(LL_RUS, V_EMO) : new Collection();

        $this->llmConfig = $request->llmConfig();
        $finalName = explode('_', $request->llm);
        $finalLlm = FINAL_LLM . '_' . end($finalName) . self::FINAL_RANK[$request->llm_quality] . ($request->emotions ? '_emo' : '');

        $previous = Poetry::whereLifeId($life->id)
            ->whereLlm($finalLlm)
            ->whereLang(LL_RUS)
            ->pluck('ix_text');

        foreach ($poetryOriginal as $ix => $paragraph) {
            if ($previous->count() < $poetryOriginal->count() && $previous->contains($paragraph->ix_text)) {
                continue;
            }

            $llmText = $this->paragraphFromLlm($ix, $request->emotions, $poetryOriginal, $poetryAlpha, $poetryBeta, $poetryEmotional);
            $finalModel = $paragraph->llmModification($llmText, LL_RUS, $finalLlm);
            Poetry::whereLifeId($life->id)
                ->whereLlm($finalLlm)
                ->whereLang(LL_RUS)
                ->whereIxText($finalModel->ix_text)
                ->delete();
            $finalModel->save();
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }

    private int $_skipIX = -1;
    private function paragraphFromLlm(int $ix, bool $emo, Collection $original, Collection $alpha, Collection $beta, Collection $emotion): string
    {
        if (str_starts_with($alpha->get($ix)->text, self::MARK_SKIP)) {
            return 'SKIP';
        }
        if ($this->_skipIX >= $ix) {
            return 'SPLICE';
        }

        $poetryOrigin = $original->get($ix);
        $poetryAlpha = $alpha->get($ix);
        $poetryBeta = $beta->get($ix);
        $poetryEmo = $emo ? $emotion->get($ix) : null;

        if (str_ends_with($alpha->get($ix)->text, self::MARK_SPLICE)) {
            $this->_skipIX = $ix;
            do {
                $this->_skipIX++;
                $poetryOrigin->text = str_replace(self::MARK_SPLICE, ' ', $poetryOrigin->text . $original->get($this->_skipIX)?->text);
                $poetryAlpha->text = str_replace(self::MARK_SPLICE, ' ', $poetryAlpha->text . $alpha->get($this->_skipIX)?->text);
                $poetryBeta->text = str_replace(self::MARK_SPLICE, ' ', $poetryBeta->text . $beta->get($this->_skipIX)?->text);
                if ($poetryEmo) {
                    $poetryEmo->text = str_replace(self::MARK_SPLICE, ' ', $poetryEmo->text . $original->get($this->_skipIX)?->text);
                }
            } while (str_ends_with((string)$alpha->get($this->_skipIX)?->text, self::MARK_SPLICE));
        }

        $finalize = new FinalWithLlm();
        $finalize->useConfig($this->llmConfig);
        return $finalize->combineParts($poetryOrigin, $poetryAlpha, $poetryBeta, $poetryEmo);
    }
}
