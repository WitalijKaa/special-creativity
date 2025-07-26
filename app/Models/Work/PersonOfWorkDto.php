<?php

namespace App\Models\Work;

use App\Models\Person\Person;

class PersonOfWorkDto
{
    public function __construct(public Person $person, public int $days)
    {
    }
}
