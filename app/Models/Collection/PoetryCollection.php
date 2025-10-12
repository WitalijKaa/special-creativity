<?php

namespace App\Models\Collection;

use App\Models\Poetry\Poetry;
use Illuminate\Support\Collection;

class PoetryCollection extends AbstractCollection
{
    public function partitions(): Collection
    {
        $parts = [];
        $prevItem = null;
        $skipIX = -1;
        foreach ($this->items as $ixItem => $item) {
            if ($ixItem <= $skipIX) {
                continue;
            }
            /** @var \App\Models\Poetry\Poetry $item */
            /** @var \App\Models\Poetry\Poetry|null $nextItem */
            $nextItem = array_key_exists($ixItem + 1, $this->items) ? $this->items[$ixItem + 1] : null;

            if (!$prevItem || !static::isSamePartition($item, $prevItem)) {
                $parts[] = [];
            }
            $ixPart = count($parts) - 1;

            $parts[$ixPart][] = $item;

            if ($nextItem &&
                !static::isSamePartition($item, $nextItem))
            {
                if ($this->countPartitionParagraphs($ixItem) == 1 && ($nextSingles = $this->countNextSingleParagraphPartitions($ixItem))) {
                    $skipIX = $ixItem + $nextSingles;
                    collect(range(1, $nextSingles))
                        ->each(function ($i) use (&$parts, $ixPart, $ixItem) {
                            $parts[$ixPart][] = $this->items[$ixItem + $i];
                        });
                }
                else if ($this->countNextSingleParagraphPartitions($ixItem) == 1) {
                    $parts[$ixPart][] = $nextItem;
                    $skipIX = $ixItem + 1;
                }
            }

            $prevItem = $item;
        }
        return collect(array_map(fn (array $part) => collect($part), $parts));
    }

    private static function isSamePartition(Poetry $itemA, Poetry $itemB): bool
    {
        return $itemA->part == $itemB->part;
    }

    private function countPartitionParagraphs(int $itemIX): int
    {
        $count = 0;
        do {
            $item = $this->items[$itemIX + $count];
            $nextItem = $this->items[$itemIX + $count + 1] ?? null;
            $count++;
        } while ($nextItem && static::isSamePartition($item, $nextItem));
        $minusIX = -1;
        while (!empty($prevItem = $this->items[$itemIX + $minusIX] ?? null) && static::isSamePartition($item, $prevItem)) {
            $count++;
            $minusIX--;
        }
        return $count;
    }

    private function countNextSingleParagraphPartitions(int $itemIX): int
    {
        $count = -1;
        do {
            $count++;
            $nextItem = $this->items[$itemIX + $count + 1] ?? null;
            $nextNextItem = $this->items[$itemIX + $count + 2] ?? null;
        } while ($nextItem && (!$nextNextItem || !static::isSamePartition($nextItem, $nextNextItem)));
        return $count;
    }
}
