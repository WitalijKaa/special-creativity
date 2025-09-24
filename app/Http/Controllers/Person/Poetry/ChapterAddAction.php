<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ChapterAddRequest;

class ChapterAddAction
{
    public function __invoke(int $life_id, ChapterAddRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $chapter = null;
        $part = 0;
        $lastIX = 0;
        $yearBegin = $life->begin;
        $yearEnd = $life->end;
        $paragraphCreatedAtLastStep = false;
        $isCorrectPartType = false;
        $spectrum = Poetry::SPECTRUM_MAIN;
        $paragraphs = collect(explode("\n", trim($request->chapter)))
            ->map(function (string $paragraph)
                use ($request,
                    $life,
                    &$chapter,
                    &$part,
                    &$lastIX,
                    &$yearBegin,
                    &$yearEnd,
                    &$paragraphCreatedAtLastStep,
                    &$isCorrectPartType,
                    &$spectrum)
            {
                $model = new Poetry();
                $model->text = trim($paragraph);
                if (!$model->text) { return null; }

                $chapter = $chapter ?? $this->findChapter($model->text);
                $part = $this->findPart($model->text, $paragraphCreatedAtLastStep, $part);

                $model->chapter = $chapter;
                $model->part = $part;
                [$yearBegin, $yearEnd] = $this->yearsBeginEnd($model->text, $yearBegin, $yearEnd);
                $isParagraph = $this->isParagraph($model->text);
                $isCorrectPartType = $this->isPartAboutCurrentTypeOfLife($model->text, $life, $isCorrectPartType);

                if (str_starts_with($model->text, 'РАЗДЕЛ ')) {
                    $spectrum = str_contains($model->text, '(размышления)') ? Poetry::SPECTRUM_PHILOSOPHY : Poetry::SPECTRUM_MAIN;
                }

                if (!$isParagraph || !$isCorrectPartType) { return null; }

                $model->life_id = $life->id;
                $model->person_id = $life->person->id;
                $model->lang = $request->lang;
                $model->begin = $yearBegin;
                $model->end = $yearEnd;
                $model->ix_text = ++$lastIX;
                $model->spectrum = $spectrum;

                $paragraphCreatedAtLastStep = $isParagraph;
                return $model;
            })
            ->filter();

        if ($paragraphs->isNotEmpty()) {
            Poetry::whereLifeId($life->id)->whereNull('ai')->delete();
            $paragraphs->each(function (Poetry $model) {
                $model->save();
            });
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }

    private function findChapter(string $paragraph): int
    {
        $paragraph = explode(" ", $paragraph);
        if ('ГЛАВА' != $paragraph[0] || !is_numeric($paragraph[1])) {
            throw new \Exception('Wrong chapter text format!');
        }
        return (int)$paragraph[1];
    }

    private function findPart(string $paragraph, bool $paragraphCreatedAtLastStep, int $currentPart): int
    {
        if (str_starts_with($paragraph, 'РАЗДЕЛ ') || str_starts_with($paragraph, 'ПОДРАЗДЕЛ ')) {
            $currentPart = (int)$currentPart;
            return $paragraphCreatedAtLastStep || !$currentPart ? $currentPart + 1 : $currentPart;
        }

        if (!$currentPart && $this->isParagraph($paragraph)) {
            throw new \Exception('Wrong text format!');
        }
        return $currentPart;
    }

    private function isPart(string $paragraph): bool
    {
        return str_starts_with($paragraph, 'РАЗДЕЛ ') || str_starts_with($paragraph, 'ПОДРАЗДЕЛ ');
    }

    private function isParagraph(string $paragraph): bool
    {
        return !$this->isPart($paragraph) && !str_starts_with($paragraph, 'ГЛАВА ');
    }

    private function isPartAboutCurrentTypeOfLife(string $paragraph, Life $life, bool $currentIs): bool
    {
        if ($this->isPart($paragraph)) {
            return in_array('Аллоды', explode(' ', $paragraph)) ?
                $life->type == Life::ALLODS :
                $life->type == Life::PLANET;
        }
        return $currentIs;
    }

    private function yearsBeginEnd(string $paragraph, int $yearBegin, int $yearEnd): array
    {
        if (!$this->isPart($paragraph) || !str_contains($paragraph, 'год')) {
            return [$yearBegin, $yearEnd];
        }
        $parts = explode(' ', $paragraph);
        $lastPart = $parts[count($parts) - 1];
        $lastPart = str_replace(['(', ')'], '', $lastPart);
        if (str_contains($lastPart, '-')) {
            $parts = explode('-', $lastPart);
            return [(int)$parts[0], (int)$parts[1]];
        } else {
            return [(int)$lastPart, (int)$lastPart];
        }
    }
}
