<?php

namespace App\Models\Person;

use App\Models\World\Life;

class PersonView
{
    public function labelLives(Person $model): string
    {
        if (!$model->count_man_lives && !$model->count_woman_lives) {
            return 'Has zero Lives on a Planet';
        }
        return ($model->count_man_lives ? 'MAN ' . $model->count_man_lives : '') .
               ($model->count_woman_lives ? 'WOMAN ' . $model->count_woman_lives : '');
    }

    public function labelLivesTotalSimple(Person $model): string
    {
        if (!$model->count_man_lives && !$model->count_woman_lives) {
            return '';
        }
        return ($model->count_man_lives + $model->count_woman_lives) . 'lives';
    }

    public function labelForce(Person|Life $model): string
    {
        if ($model instanceof Life) {
            return 'at the start FORCE Life ' . $model->begin_force_person . ' for girl ' . $model->begin_force_woman;
        }
        return 'FORCE for Life ' . $model->force_person . ' for girl ' . $model->force_woman;
    }
}
