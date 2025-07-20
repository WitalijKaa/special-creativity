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
            EventType::DEEP_LOVE, EventType::ONCE_LOVE, EventType::MASS_LOVE => CC_DANGER,
            EventType::SLAVE_JOB, EventType::SLAVE_WOMAN_LIFE => CC_DARK,
            EventType::HOLY_LIFE => CC_PRIMARY,
            PersonEventSynthetic::ALLODS => CC_INFO,
            default => CC_STUB,
        };
    }

    public function labelRange(PersonEvent|PersonEventSynthetic $model): string
    {
        return ($model->begin == $model->end ? $model->begin : ($model->begin . '-' . $model->end)) . 'Y';
    }

    public function labelGenreMy(PersonEvent|PersonEventSynthetic $model, bool $myLabel): string
    {
        return $myLabel ? $this->labelGenre($model) : '';
    }

    public function labelGenre(PersonEvent|PersonEventSynthetic $model): string
    {
        return $this->gender($model->life->role);
    }
}
