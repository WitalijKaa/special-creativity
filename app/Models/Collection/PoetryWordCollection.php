<?php

namespace App\Models\Collection;

use App\Models\Person\Person;

class PoetryWordCollection extends AbstractCollection
{
    public function wordTip(string $str): ?string
    {
        $dashed = str_contains($str, '-');
        foreach ($this->items as $item) {
            /** @var $item \App\Models\Poetry\PoetryWord */

            if ($dashed && !str_contains($item->word, '-')) {
                continue;
            }

            $wordList = explode(' ', $item->word);
            if (array_any($wordList, fn($word) => str_contains($str, $word))) {
                return $item->definition;
            }
        }
        return null;
    }

    private array $_names;
    public function isName(string $str): bool {
        if (empty($this->_names)) {
            $this->_names = Person::select('name')->pluck('name')->toArray();
        }
        $str = str_replace(['.', ','], '', $str);
        return in_array($str, $this->_names);
    }
}
