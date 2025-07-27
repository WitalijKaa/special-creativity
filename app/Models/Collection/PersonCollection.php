<?php

namespace App\Models\Collection;

use App\Models\Person\Person;
use App\Models\Person\PersonEvent;
use App\Models\Work\PersonOfWorkDto;
use Illuminate\Support\Collection;

class PersonCollection extends AbstractCollection
{
    public function pushUnique(Collection|Person $model): static
    {
        if ($model instanceof Collection) {
            $model->each(fn (Person $person) => $this->pushUniqueTech($person));
        } else {
            $this->pushUniqueTech($model);
        }
        return $this;
    }

    private function pushUniqueTech(Person $model): void
    {
        foreach ($this as $person) {
            if ($person->id == $model->id) {
                return;
            }
        }
        $this->push($model);
    }

    public function pushUniqueWorkers(int $year, PersonEvent $workEvent): static
    {
        foreach ($workEvent->all_lives as $life) {
            $this->pushUniqueWorker(
                new PersonOfWorkDto(
                    $life->person,
                    $life->lifeWork->daysOfWorkEventOfYear($year, $workEvent->id)
                )
            );
        }
        return $this;
    }

    public function pushUniqueWorker(PersonOfWorkDto $model): static
    {
        foreach ($this as $personWorker) {
            /** @var PersonOfWorkDto $personWorker */
            if ($personWorker->person->id == $model->person->id) {
                $personWorker->days += $model->days;
                return $this;
            }
        }
        $this->push($model);
        return $this;
    }
}
