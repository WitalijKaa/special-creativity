<?php

namespace App\Http\Controllers\Person\Routine;

use App\Models\World\ForceEvent;

class ReWriteForceVsRoutineAction
{
    public function __invoke()
    {
        ForceEvent::reWriteCreationsAndLives();
        return redirect()->back();
    }
}
