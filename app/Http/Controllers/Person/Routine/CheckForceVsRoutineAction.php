<?php

namespace App\Http\Controllers\Person\Routine;

use App\Models\World\ForceEvent;

class CheckForceVsRoutineAction
{
    public function __invoke()
    {
        ForceEvent::recalculateCreationsAndForce();
        return redirect()->back();
    }
}
