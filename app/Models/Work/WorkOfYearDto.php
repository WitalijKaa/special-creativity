<?php

namespace App\Models\Work;

use App\Models\Person\PersonEvent;
use App\Models\World\Work;

class WorkOfYearDto
{
    public int $days = 0;
    public int $hours = 0;

    public function __construct(public PersonEvent $event, public Work $work)
    {
    }
}
