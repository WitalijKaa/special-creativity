<?php

namespace App\Models\Work;

use App\Models\Collection\PersonCollection;

class WorkCalculationsDto
{
    public int $begin;
    public int $end;
    public int $days = 0;
    public float $workYears = 0;

    /** @var array|\App\Models\Work\YearOfWorkDto[] */
    public array $worksPerYear = [];
    public PersonCollection $workers;
}
