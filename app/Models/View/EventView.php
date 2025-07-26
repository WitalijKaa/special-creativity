<?php

namespace App\Models\View;

use App\Models\Person\EventType;
use App\Models\Person\PersonEvent;
use App\Models\Person\PersonEventSynthetic;
use App\Models\World\Life;
use App\Models\World\LifeWork;

class EventView extends AbstractView
{
    public function backColor(PersonEvent|PersonEventSynthetic $model): string
    {
        if ($model instanceof PersonEventSynthetic) {
            return match ($model->type_id) {
                PersonEventSynthetic::ALLODS => CC_INFO,
                default => CC_STUB,
            };
        }

        if ($model->type->is_honor) {
            return CC_PRIMARY;
        }
        if (in_array($model->type_id, [EventType::DEEP_LOVE, EventType::ONCE_LOVE, EventType::EMPTY_LOVE, EventType::DIRTY_LOVE])) {
            return CC_DANGER;
        }
        if ($model->type->is_relation) {
            return CC_WARNING;
        }
        if ($model->type->is_work) {
            return CC_SUCCESS;
        }
        if ($model->type->is_slave) {
            return CC_SECONDARY;
        }
        return CC_STUB;
    }

    public function backColorType(EventType $model): string
    {
        if ($model->is_honor) {
            return CC_PRIMARY;
        }
        if ($model->is_relation) {
            return CC_DANGER;
        }
        if ($model->is_work) {
            return CC_SUCCESS;
        }
        if ($model->is_slave) {
            return CC_SECONDARY;
        }
        return CC_DARK;
    }

    public function labelRange(PersonEvent|PersonEventSynthetic $model): string
    {
        return '[' . ($model->begin == $model->end ? $model->begin . 'Y' : ($model->begin . '-' . $model->end . 'Y' . '<small><small>' . ($model->end - $model->begin) . '</small></small>')) . ']';
    }

    public function loveConnectionGenre(PersonEvent|PersonEventSynthetic $model, Life $viewLife): string
    {
        if ($model instanceof PersonEventSynthetic || !$model->type->is_relation) {
            return '';
        }
        $genre = '';
        if ($model->life_id != $viewLife->id) {
            $genre .= $this->gender($model->life->role);
        }
        foreach ($model->connections as $connect) {
            if ($connect->life_id != $viewLife->id) {
                $genre .= $this->gender($connect->life->role);
            }
        }
        return $genre;
    }

    public function labelStartAge(PersonEvent|PersonEventSynthetic $model, Life $viewLife): string
    {
        if ($model instanceof PersonEventSynthetic) {
            return '';
        }
        return '<u>_' . ($model->begin - $viewLife->begin)  . '_</u>' .
            ($model->begin == $model->end ? '' : '<u>-_' . ($model->end - $viewLife->begin)  . '_</u>');
    }

    public function labelWork(PersonEvent|PersonEventSynthetic $model): string
    {
        if (!$model->work_id) {
            return '';
        }
        return '<small>' . $model->work->name  . '</small>' .
            (!$model->strong ? '' : ' <em>' . $model->strong .'%</em>');
    }

    public function labelWorkDays(PersonEvent|PersonEventSynthetic $model, ?LifeWork $lifeWork): string
    {
        if (!$lifeWork || !$model->work_id) {
            return $this->space2();
        }
        return $this->space2() . '💪🏻 ' . $lifeWork->days($model->work);
    }
}
