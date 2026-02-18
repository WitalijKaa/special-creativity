<?php

namespace App\Models\Biome;

use ModelsAlpha\BaseModel;

class BirthChildDesire extends BaseModel
{
    public int $period;

    public int $lastAt = 0; // age
    public int $from = 17;
    public int $until = 58;

    public function wishToBornNow(int $age): bool
    {
        return $this->from <= $age && $age <= $this->until && ($this->lastAt + $this->period <= $age);
    }

    public function bornChild(int $age): void
    {
        $this->lastAt = $age;
    }
}
