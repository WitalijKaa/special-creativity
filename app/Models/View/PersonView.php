<?php

namespace App\Models\View;

use App\Models\Collection\LifeCollection;
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
        return '[' . $model->author->name . '-' . $model->created_vs_number . '] <small><small> ' . $model->begin . 'Y</small></small>';
    }

    public function labelCreations(Person $model, ?int $year): string
    {
        $count = $year > 0 ? $model->creations->where('begin', '<=', $year)->count() : $model->creations->count();
        return !$count ? '' : static::SPACE . ' <small>🌌</small>' . $count;
    }

    public function labelVizavi(Person $model): string
    {
        if (!$model->vizavi->count()) {
            return '';
        }
        $return = '';
        foreach ($model->vizavi as $vizavi) {
            $return .= ' ' . $vizavi->name;
        }
        if ($model->vizavi->count() > 1) {
            $return = '<small><small>' . $return . '</small></small>';
        }
        $return = '<small>❤️</small>' . $return;
        return $return;
    }

    public function labelHolyLife(Person $model, ?int $year): string
    {
        $count = $year > 0 ? $model->countHolyLivesBeforeYear($year) : $model->count_holy_lives;
        return !$count ? '' : '<small>👼🏻 ' . $count . '</small> ';
    }

    public function labelSlaveLife(Person $model, ?int $year): string
    {
        $count = $year > 0 ? $model->countSlaveLivesBeforeYear($year) : $model->count_slave_lives;
        return !$count ? '' : '<small>⛏ ' . $count . '</small> ';
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
                return '';
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

    public function lifeBackColor(Life $model): string
    {
        if (!$model->is_planet) { return MM_BLUE; }

        if (!$model->is_man) {
            if ($model->is_holy && $model->is_slave) {
                return MM_ORANGE_L2;
            }

            if ($model->is_slave) {
                return MM_LIME_L2;
            }

            return $model->is_holy ? MM_TEAL_L2 : MM_GREEN_L2;
        }

        if ($model->is_holy && $model->is_slave) {
            return MM_ORANGE_D1;
        }

        if ($model->is_slave) {
            return MM_LIME;
        }

        if (!$model->is_holy && $model->is_worker) {
            return MM_LIGHT_GREEN;
        }

        return $model->is_holy ? MM_TEAL : MM_GREEN_D1;
    }

    public function labelLifeShort(Life $life): string
    {
        return $life->is_planet ?
            ($life->current_type_no . ' ' . $life->person->name . ' ' . ($life->end - $life->begin)) :
            ($life->end - $life->begin);
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

    public function labelPersonOfYear(Life $life, int $year, LifeCollection $allAtYear): string
    {
        $count = 1;

        $names = $life->person->name . $this->gender($life->role);
        foreach ($allAtYear as $otherLife) {
            if ((($year - $life->begin) != ($year - $otherLife->begin)) ||
                $life->id == $otherLife->id)
            {
                continue;
            }

            $names .= ' ' . $otherLife->person->name . $this->gender($otherLife->role);
            $count++;
        }

        $count = '<div>' .
            '<span class="badge bg-secondary rounded-pill"><u>_' . ($year - $life->begin) . '_</u></span> ' .
            '<span class="badge bg-' . ($life->is_allods ? CC_PRIMARY : CC_SUCCESS) . ' rounded-pill">' . $count . '</span>' .
            '</div>';

        return '<div class="w-75">' . $names . ' <u>_' . ($year - $life->begin) . '_</u></div>' . $count;
    }
}
