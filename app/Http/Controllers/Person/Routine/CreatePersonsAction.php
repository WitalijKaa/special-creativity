<?php

namespace App\Http\Controllers\Person\Routine;

use App\Models\Collection\LifeCollection;
use App\Models\Person\Person;
use App\Models\World\ForceEvent;
use App\Models\World\Life;

class CreatePersonsAction
{
    public function __invoke()
    {
        $persons = Person::all();

        $lives = new LifeCollection();
        foreach ($persons as $person) {
            $lastLife = $person->last_life;
            if ($lastLife->is_allods && $lastLife->begin_force_person >= 100) {
                $lives->push($lastLife);
            }
        }

        $lives = $lives->sortByEnd();

        $zvezdi = 97;

        foreach ($lives as $life) {
            /** @var $life Life */
            $author = Person::whereId($life->person_id)->first();

            $model = new Person();
            $model->name = $this->nextName();
            $model->nick = 'Zvezdi ' . $zvezdi;
            $model->person_author_id = $author->id;
            $model->type = Person::IMPERIUM;
            $model->begin = $life->end;

            $model->save();
            ForceEvent::createPerson($author, $life->end);
            $zvezdi++;
        }

        return redirect(route('web.basic.space'));
    }

    private function nextName()
    {
        $id = (string)(Person::count() + 1);

        if ($id > 400) {
            return (strlen($id) > 2 ? $this->nOne(substr($id, -3, 1)) . '-' : '') .
                (strlen($id) > 1 ? $this->nThree(substr($id, -2, 1)) . '-' : '') .
                (strlen($id) > 0 ? $this->nTwo(substr($id, -1)) : '');
        }

        if ($id > 200) {
            return (strlen($id) > 2 ? $this->nTwo(substr($id, -3, 1)) . '-' : '') .
                (strlen($id) > 1 ? $this->nOne(substr($id, -2, 1)) . '-' : '') .
                (strlen($id) > 0 ? $this->nThree(substr($id, -1)) : '');
        }

        if ($id > 99) {
            return (strlen($id) > 2 ? $this->nThree(substr($id, -3, 1)) . '-' : '') .
                (strlen($id) > 1 ? $this->nOne(substr($id, -2, 1)) . '-' : '') .
                (strlen($id) > 0 ? $this->nTwo(substr($id, -1)) : '');
        }

        return (strlen($id) > 1 ? $this->nTwo(substr($id, -2, 1)) . '-' : '') .
            (strlen($id) > 0 ? $this->nOne(substr($id, -1)) : '');
    }

    private function nOne(string $id): string
    {
        return match ($id) {
            '0' => 'Zera',
            '1' => 'Onna',
            '2' => 'Lola',
            '3' => 'Tiri',
            '4' => 'Ctvr',
            '5' => 'Peta',
            '6' => 'Wesa',
            '7' => 'Sedi',
            '8' => 'Vosi',
            '9' => 'Devv',
        };
    }

    private function nTwo(string $id): string
    {
        return match ($id) {
            '0' => 'Ziik',
            '1' => 'Onia',
            '2' => 'Lall',
            '3' => 'Tria',
            '4' => 'Citi',
            '5' => 'Piat',
            '6' => 'Wwst',
            '7' => 'Sdam',
            '8' => 'Viis',
            '9' => 'Dtea',
        };
    }

    private function nThree(string $id): string
    {
        return match ($id) {
            '0' => 'Zaja',
            '1' => 'Oana',
            '2' => 'Liva',
            '3' => 'Terr',
            '4' => 'Ceet',
            '5' => 'Ptka',
            '6' => 'Wisa',
            '7' => 'Sima',
            '8' => 'Voos',
            '9' => 'Divi',
        };
    }
}
