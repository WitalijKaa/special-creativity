<?php

namespace App\Models\Work;

use App\Models\Collection\PersonCollection;

/**
 * @property PersonCollection|\App\Models\Work\PersonOfWorkDto[] $workers
 */
class YearOfWorkDto
{
    public int $days = 0;

    public function __construct(public PersonCollection $workers)
    {
    }
}
