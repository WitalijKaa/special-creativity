<?php

namespace App\Models\Biome;

use ModelsAlpha\BaseModel;

class Humanoid extends BaseModel
{
    public string $name;
    public int $yearOfBorn;
    public int $yearOfDeath;
    public bool $sex;

    public BirthChildDesire $desireChild;

    public function age(int $year): int
    {
        return $year - $this->yearOfBorn;
    }

    public function isAlive(int $year): bool
    {
        return $this->yearOfDeath >= $year && $year >= $this->yearOfBorn;
    }
}
