<?php

namespace App\Models\View;

use App\Models\Person\EventType;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventSynthetic;

class EventView extends AbstractView
{
    public function backColor(PersonEvent|PersonEventSynthetic $model): string
    {
        return match ($model->type_id) {
            EventType::DEEP_LOVE => CC_DANGER,
            default => CC_STUB,
        };
    }

    public function labelRange(PersonEvent|PersonEventSynthetic $model): string
    {
        return ($model->begin == $model->end ? $model->begin : ($model->begin . '-' . $model->end)) . 'Y';
    }
}
