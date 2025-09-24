<?php

namespace App\Models\Collection;

use App\Models\Person\Person;
use App\Models\Poetry\Llm\PoetryWordLlm;
use App\Models\Poetry\Poetry;
use App\Models\Poetry\PoetryWord;
use App\Models\Poetry\PoetryWordParsedDto;
use Illuminate\Support\Collection;

class PoetryWordCollection extends AbstractCollection
{
    private const TIP_NO = 0;
    private const TIP_ON_SINGLE_WORD = 1;
    private const TIP_ON_COUPLE_WORD = 2;

    public function findPoetryWord(string $word, ?string $nextWord): ?PoetryWord
    {
        $this->parseWords();
        foreach ($this->_wordsArr as $item) {
            if ($this->isTipWord($word, $nextWord, $item)) {
                return $item->model;
            }
        }
        return null;
    }

    public function parsePoetryWord(string $word, ?string $nextWord): ?array
    {
        $this->parseWords();
        foreach ($this->_wordsArr as $item) {
            if ($onWordType = $this->isTipWord($word, $nextWord, $item)) {
                return [$item->definition, self::TIP_ON_COUPLE_WORD == $onWordType];
            }
        }
        return null;
    }

    private function isTipWord(string $word, ?string $nextWord, PoetryWordParsedDto $check): int
    {
        if ($check->isCouple && !$nextWord) {
            return self::TIP_NO;
        }
        if (($check->isDashed && !str_contains($word, '-')) ||
            (!$check->isDashed && str_contains($word, '-')))
        {
            return self::TIP_NO;
        }

        $word = trim(mb_strtolower($this->cleanWord($word)));
        if ($check->isCouple) {
            $nextWord = $nextWord ? trim(mb_strtolower($this->cleanWord($nextWord))) : $nextWord;
            if (array_any($check->words,
                fn($checkCouple) => str_contains($word, $checkCouple[0]) && str_contains($nextWord, $checkCouple[1])))
            {
                return self::TIP_ON_COUPLE_WORD;
            }
        }
        else if ($check->isDashed) {
            $word = explode('-', $word);

            foreach ($check->words as $checkDashed) {
                if (count($checkDashed) != count($word)) {
                    return self::TIP_NO;
                }
                $contains = true;
                foreach ($checkDashed as $ixW => $wordForCheck) {
                    if (!str_contains($word[$ixW], $wordForCheck)) {
                        $contains = false;
                        break;
                    }
                }
                if ($contains) {
                    return self::TIP_ON_SINGLE_WORD;
                }
            }
        }
        else {
            if (array_any($check->words, fn($checkWord) => str_contains($word, $checkWord))) {
                return self::TIP_ON_SINGLE_WORD;
            }
        }
        return self::TIP_NO;
    }

    /** @var array<\App\Models\Poetry\PoetryWordParsedDto>  */
    private array $_wordsArr;
    private function parseWords(): void
    {
        if (empty($this->_wordsArr)) {
            $this->_wordsArr = [];

            foreach ($this->items as $item) {
                /** @var $item \App\Models\Poetry\PoetryWord */

                $dto = new PoetryWordParsedDto();
                $dto->model = $item;
                $dto->definition = $item->definition;
                $dto->isDashed = str_contains($item->word, '-');
                $dto->words = explode(',', $item->word);
                $dto->words = array_map(fn(string $w) => trim(mb_strtolower($w)), $dto->words);
                foreach ($dto->words as $word) {
                    $explode = explode(' ', $word);
                    if (count($explode) == 2) {
                        $dto->isCouple = true;
                        break;
                    }
                }
                if ($dto->isCouple) {
                    $words = [];
                    foreach ($dto->words as $word) {
                        $explode = explode(' ', $word);
                        if (count($explode) == 2) {
                            $words[] = $explode;
                            $words[] = [$explode[1], $explode[0]];
                        }
                    }
                    $dto->words = $words;
                }
                else if ($dto->isDashed) {
                    $words = [];
                    foreach ($dto->words as $word) {
                        $words[] = explode('-', $word);
                    }
                    $dto->words = $words;
                }

                $this->_wordsArr[] = $dto;
            }
        }
    }

    private array $_names;
    public function isName(string $str): bool {
        $this->_names ??= Person::select('name')->pluck('name')->toArray();
        return in_array($this->cleanWord($str), $this->_names);
    }

    private function cleanWord(string $str): string
    {
        return str_replace(['.', ',', ':'], '', $str);
    }

    public static function findAllNames(Collection $paragraphs): array
    {
        $namesAll = Person::select('name')->pluck('name');
        $return = [];
        foreach ($paragraphs as $paragraph) {
            foreach ($namesAll as $name) {
                if (in_array($name, $return)) {
                    continue;
                }
                if (str_contains($paragraph, $name)) {
                    $return[] = $name;
                }
            }
        }
        return $return;
    }

    public function filterByParagraphs(Collection $paragraphs): static
    {
        $specialWords = new static();
        foreach ($paragraphs as $paragraph) {
            $isNextWordTip = false;
            foreach(explode(' ', $paragraph->text) as $ixW => $word) {
                if ($isNextWordTip) {
                    $isNextWordTip = false;
                    continue;
                }
                $nextWord = Poetry::isEndingWord($word) || empty($pList[$ixW + 1]) ? null : $pList[$ixW + 1];
                $specialWords->push($this->findPoetryWord($word, $nextWord));

            }
        }
        $hasID = [];
        return $specialWords->filter()->filter(function (PoetryWord $word) use (&$hasID) {
            if (!in_array($word->id, $hasID)) {
                $hasID[] = $word->id;
                return true;
            }
            return false;
        })->values();
    }

    public function toLlm(): Collection
    {
        $return = new Collection();
        $this->each(fn(PoetryWord $word) => $return->add(PoetryWordLlm::byDbModel($word)));
        return $return;
    }
}
