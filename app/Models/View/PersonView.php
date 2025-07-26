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
            return parent::space4();
        }
        return '<small>[' . $model->author->name . '-' . (1 + $model->author->creations->search(fn (Person $created) => $created->id == $model->id)) . '] <small><small> ' . $model->begin . 'Y</small></small></small>' . parent::space4();
    }

    public function labelCreations(Person $model, ?int $year): string
    {
        $count = $year > 0 ? $model->creations->where('begin', '<=', $year)->count() : $model->creations->count();
        return !$count ? '' : static::SPACE . ' <small>🌌</small>' . $count;
    }

    public function labelVizavi(Person $model): string
    {
        return $model->only_vizavi ? '<small><small><small><small>❤️</small></small> ' . $model->only_vizavi?->name . '</small></small>' . parent::space4() : parent::space4();
    }

    public function labelHolyLife(Person $model): string
    {
        $count = $model->count_holy_lives;
        return !$count ? '' : '<small><small>👼🏻</small> ' . $count . '</small> ';
    }

    public function labelSlaveLife(Person $model): string
    {
        $count = $model->count_slave_lives;
        return !$count ? '' : '<small><small>⛏</small> ' . $count . '</small> ';
    }

    public function labelLifeIsHoly(Life $model): string
    {
        return !$model->is_holy ? '' : '<small>👼🏻</small> ';
    }

    public function labelLifeIsDeepLove(Life $model): string
    {
        return !$model->is_deep_love ? '' : '<small>❤️</small> ';
    }

    public function labelLifeIsSlave(Life $model): string
    {
        return !$model->is_slave ? '' : '<small>⛏</small> ';
    }

    public function labelLives(Person $model, ?int $year): string
    {
        if (!$model->countManLives($year) && !$model->countWomanLives($year)) {
            if ($year) {
                return 'First living';
            }
            return 'Has zero Lives on a Planet';
        }
        return ($model->countManLives($year) ? $this->gender(Life::MAN) . $model->countManLives($year) : '') .
               ($model->countWomanLives($year) ? $this->gender(Life::WOMAN) . $model->countWomanLives($year) : '');
    }

    public function labelLivesTotalSimple(Person $model, ?int $year): string
    {
        if (!$model->countManLives($year) && !$model->countWomanLives($year)) {
            return '';
        }
        return ($model->countManLives($year) + $model->countWomanLives($year)) . ' lives';
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

    public function lifeBack(?Life $model): string
    {
        return match ($model?->type) {
            Life::ALLODS => CC_PRIMARY,
            Life::PLANET => CC_SUCCESS,
            Life::DREAM => CC_DARK,
            Life::VIRTUAL => CC_WARNING,
            default => CC_SECONDARY,
        };
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

    public function labelAge(?Life $planetLife, ?int $year): string
    {
        if (!$year || !$planetLife?->is_planet) {
            return '';
        }
        return ' <u>_' . ($year - $planetLife->begin) . '_</u>';
    }
}
