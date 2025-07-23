<?php

namespace App\Models\View;

use App\Models\World\Life;

class AbstractView
{
    public const string SPACE = '&nbsp;&nbsp;&nbsp;&nbsp;';

    protected function gender(int $gender): string
    {
        return match ($gender) {
            Life::MAN => ' <small>🧑🏻</small> ',
            Life::WOMAN => ' <small>👩🏻</small> ',
            default => '',
        };
    }

    public function lifeGenre(Life $model): string
    {
        return $this->gender($model->role);
    }

    public function space2(): string
    {
        return self::SPACE . self::SPACE;
    }

    public function space4(): string
    {
        return self::SPACE . self::SPACE . self::SPACE . self::SPACE;
    }
}
