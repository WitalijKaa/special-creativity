<?php

namespace App\Models\Collection;

use Illuminate\Support\Collection;

class AbstractCollection extends Collection
{
    public static function toCollection(Collection $dbCollection): static
    {
        return new static($dbCollection);
    }
}
