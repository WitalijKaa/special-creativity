<?php

namespace App\Http\Controllers\Person\Poetry;

use App\Models\Poetry\Poetry;
use App\Models\World\Life;
use App\Requests\Poetry\ChapterAddRequest;

class ChapterAddAction
{
    private const array CHAPTER = ['ГЛАВА ', 'CHAPTER '];
    private const array PART = ['РАЗДЕЛ ', 'PART '];
    private const array SUBPART = ['ПОДРАЗДЕЛ ', 'SUBPART '];
    private const array ALLODS = ['Аллоды', 'Allods'];
    private const array THINKING = ['(размышления)', '(philosophy)'];

    public function __invoke(int $life_id, ChapterAddRequest $request)
    {
        if (!$life = Life::whereId($life_id)->with(['person'])->first()) {
            return redirect(route('web.person.list'));
        }

        $simpleFlowInitiated = false;
        $chapter = null;
        $part = 0;
        $lastIX = 0;
        $yearBegin = $life->begin;
        $yearEnd = $life->end;
        $isCorrectPartType = false;
        $spectrum = Poetry::SPECTRUM_MAIN;
        $paragraphs = collect(explode("\n", trim($request->chapter)))
            ->map(function (string $paragraph)
                use ($request,
                    $life,
                    &$simpleFlowInitiated,
                    &$chapter,
                    &$part,
                    &$lastIX,
                    &$yearBegin,
                    &$yearEnd,
                    &$isCorrectPartType,
                    &$spectrum)
            {
                $model = new Poetry();
                $model->text = trim($paragraph);
                if (!$model->text) { return null; }

                if (!$simpleFlowInitiated && !$chapter && !$part && $this->isParagraph($model->text)) {
                    $chapter = 1;
                    $part = 1;
                    $isCorrectPartType = true;
                    $simpleFlowInitiated = true;
                }
                if (!$simpleFlowInitiated) {
                    $simpleFlowInitiated = true;
                }

                $chapter = $chapter ?? $this->findChapter($model->text);
                $part = $this->findPart($model->text, $part);

                $model->chapter = $chapter;
                $model->part = $part;
                [$yearBegin, $yearEnd] = $this->yearsBeginEnd($model->text, $yearBegin, $yearEnd);
                $isParagraph = $this->isParagraph($model->text);
                $isCorrectPartType = $this->isPartAboutCurrentTypeOfLife($model->text, $life, $isCorrectPartType);

                if ($this->isChapter($model->text)) {
                    $spectrum = $this->itContains($model->text, self::THINKING) ? Poetry::SPECTRUM_PHILOSOPHY : Poetry::SPECTRUM_MAIN;
                }

                if (!$isParagraph || !$isCorrectPartType) { return null; }

                $model->life_id = $life->id;
                $model->person_id = $life->person->id;
                $model->lang = $request->lang;
                $model->begin = $yearBegin;
                $model->end = $yearEnd;
                $model->ix_text = ++$lastIX;
                $model->spectrum = $spectrum;
                return $model;
            })
            ->filter();

        if ($paragraphs->isNotEmpty()) {
            Poetry::whereLifeId($life->id)->whereNull('llm')->delete();
            $paragraphs->each(function (Poetry $model) {
                $model->save();
            });
        }

        return redirect(route('web.person.poetry-life', ['life_id' => $life->id]));
    }

    private function findChapter(string $paragraph): int
    {
        if (!$this->isChapter($paragraph)) {
            throw new \Exception('Wrong chapter text format!');
        }
        return (int)explode(" ", trim($paragraph))[1];
    }

    private function findPart(string $paragraph, int $currentPart): int
    {
        if ($this->isPart($paragraph)) {
            return (int)$currentPart + 1;
        }

        if (!$currentPart && $this->isParagraph($paragraph)) {
            throw new \Exception('Wrong text format!');
        }
        return $currentPart;
    }

    private function isChapter(string $paragraph): bool
    {
        $words = explode(" ", trim($paragraph));
        return $this->startsWith($paragraph, self::CHAPTER) &&
            count($words) < 11 &&
            is_numeric($words[1]);
    }

    private function isPart(string $paragraph): bool
    {
        return $this->startsWith($paragraph, self::PART) || $this->startsWith($paragraph, self::SUBPART);
    }

    private function isParagraph(string $paragraph): bool
    {
        return !$this->isPart($paragraph) && !$this->isChapter($paragraph);
    }

    private function isPartAboutCurrentTypeOfLife(string $paragraph, Life $life, bool $currentIs): bool
    {
        if ($this->isPart($paragraph)) {
            $isAllodsTitle = !!array_intersect(self::ALLODS, explode(' ', $paragraph));
            return $isAllodsTitle ?
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

    private function startsWith(string $haystack, array $needle): bool
    {
        return array_any($needle, fn($n) => str_starts_with($haystack, $n));
    }

    private function itContains(string $haystack, array $needle): bool
    {
        return array_any($needle, fn($n) => str_contains($haystack, $n));
    }
}
