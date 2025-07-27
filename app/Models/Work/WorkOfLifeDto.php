<?php

namespace App\Models\Work;

class WorkOfLifeDto
{
    public int $days = 0;
    public int $hours = 0;

    public function __construct(public Work $work)
    {
    }
}
