<?php

namespace App\Models\Work;

use App\Models\Person\PersonEvent;
use App\Models\World\LifeWork;
use App\Models\World\Work;
use Illuminate\Support\Collection;

class WorkOfLifeDto
{
    public int $days = 0;
    public int $hours = 0;

    public function __construct(public Work $work)
    {
    }
}
