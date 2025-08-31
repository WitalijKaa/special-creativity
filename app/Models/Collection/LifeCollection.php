<?php

namespace App\Models\Collection;

use App\Models\World\Life;

class LifeCollection extends AbstractCollection
{
    public static function allodsByPersonID(int $personID, ?int $fromYear = null, ?int $untilYear = null): static
    {
        return static::toCollection(
            LifeBuilder::allodsByPersonID($personID, $fromYear, $untilYear)->get()
        );
    }

    public static function allodsByRange(int $fromYear, int $untilYear): static
    {
        return static::toCollection(
            LifeBuilder::allodsByRange($fromYear, $untilYear)->get()
        );
    }

    public static function planetByRange(int $fromYear, int $untilYear): static
    {
        return static::toCollection(
            LifeBuilder::planetByRange($fromYear, $untilYear)->get()
        );
    }

    public static function livedAtTheSameTime(Life $life): static
    {
        return static::toCollection(
            LifeBuilder::livedAtTheSameTime($life)->get()
        );
    }

    public function sortByBegin(): static
    {
        return $this->sortBy('begin');
    }

    public function sortByEnd(): static
    {
        return $this->sortBy('end');
    }

    public function countAtAgeRange(int $year, int $fromAge, int $untilAge): int
    {
        $count = 0;
        foreach ($this as $life) {
            /** @var $life Life */

            $age = $year - $life->begin;
            if ($age >= $fromAge && $age <= $untilAge) {
                $count++;
            }
        }
        return $count;
    }

    public function countAtAgeRangeGender(int $gender, int $year, int $fromAge, int $untilAge): int
    {
        $count = 0;
        foreach ($this as $life) {
            /** @var $life Life */

            if ($life->role != $gender) {
                continue;
            }

            $age = $year - $life->begin;
            if ($age >= $fromAge && $age <= $untilAge) {
                $count++;
            }
        }
        return $count;
    }
}
