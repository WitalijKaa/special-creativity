<?php

namespace App\Models\World\Prediction;

class PredictionPeriodPersonsDto
{

    public int $persons;
    public int $created;

    public float $calculation;

    public function __construct(public int $begin, public int $end)
    {
    }
}
