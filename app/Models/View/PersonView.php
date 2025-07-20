<?php

namespace App\Models\View;

use App\Models\Person\Person;
use App\Models\World\ForceEvent;
use App\Models\World\Life;

class PersonView extends AbstractView
{
    public function labelAuthor(Person $model): string
    {
        if (!$model->author) {
            return '';
        }
        return '[' . $model->author->name . '-' . (1 + $model->author->creations->search(fn (Person $created) => $created->id == $model->id)) . '] <small>in ' . $model->begin . 'Y</small>';
    }

    public function labelCreations(Person $model): string
    {
        if (!$model->creations->count()) {
            return '';
        }
        return static::SPACE . ' <small>👼🏻</small>' . $model->creations->count();
    }

    public function labelLives(Person $model): string
    {
        if (!$model->count_man_lives && !$model->count_woman_lives) {
            return 'Has zero Lives on a Planet';
        }
        return ($model->count_man_lives ? ' <small>🧑🏻</small> ' . $model->count_man_lives : '') .
               ($model->count_woman_lives ? ' <small>👩🏻</small> ' . $model->count_woman_lives : '');
    }

    public function labelLivesTotalSimple(Person $model): string
    {
        if (!$model->count_man_lives && !$model->count_woman_lives) {
            return '';
        }
        return ($model->count_man_lives + $model->count_woman_lives) . ' lives';
    }

    public function labelForce(Person|Life $model): string
    {
        if ($model instanceof Life) {
            if (!$model->forceEvents->count()) {
                return 'has <small>🧪</small> ' . $model->begin_force_person;
            }
            $force = $model->begin_force_person;
            $return = $force . ' <small>🧪</small>';
            foreach ($model->forceEvents as $event) {
                $return .= $this->labelForceEventOfLife($event, $model);
                $force = $event->diffSimple($force);
            }
            $return .= ' THE END <small>🧪</small> is ' . $force;
            return $return;
        }
        return '<small>🧪</small> ' . $model->force_person;
    }

    public function labelForceEventOfLife(ForceEvent $event, Life $life): string
    {
        if ($event->diff < 0) {
            return ' used to ' . $event->type->name;
        }
        return ' rise by ' . $event->type->name . ' at ' . $event->diff;
    }

    public function labelLastYearOfExistence(Person $model): string
    {
        if (!$model->lives->count()) {
            return ' <small>⌚️</small> ' . $model->begin . 'Y';
        }
        return static::SPACE . ' <small>⌚️</small> ' . $model->last_life->end . 'Y';
    }
}
