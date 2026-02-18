<?php

namespace App\Console\Commands\GrowBiome;

use App\Models\Biome\BirthChildDesire;
use App\Models\Biome\Database\HumanoidDb;
use App\Models\Biome\Events\Core\BirthEvent;
use App\Models\Biome\Events\SenderInterface;
use App\Models\Biome\Humanoid;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class GrowBiome extends Command
{
    protected $signature = 'biome:grow {reset}';

    protected $description = 'Biome simulation with Kafka';

    private $fastName;

    public function handle(SenderInterface $sender)
    {
        $this->fastName = HumanoidDb::count() + 2;

        if ('killAll' == $this->argument('reset')) {
            HumanoidDb::where('id', '>', 0)->delete();
        }

        $this->firstWoman($sender);
        $year = 1;
        $finalYear = 220;

        $persons = new Collection();

        while ($year <= $finalYear) {
            $persons = $persons->merge(HumanoidDb::allBornAt($year));
            $persons = $persons->filter(fn (Humanoid $person) => $person->isAlive($year));

            foreach ($persons as $mama) {
                /** @var $mama Humanoid */
                if (!$mama->sex && $mama->desireChild->wishToBornNow($mama->age($year))) {
                    $sender->send($this->newChildEvent($year, $mama));
                    HumanoidDb::bornChild($mama->name, $mama->age($year));
                }
            }

            $year++;
        }
    }

    protected function firstWoman(SenderInterface $sender): void
    {
        if (HumanoidDb::count()) {
            return;
        }

        $year = 1;

        $firstWomanChildrenDesire = BirthChildDesire::fromArray(['period' => 4]);
        $firstWoman = Humanoid::fromArray(['name' => '', 'yearOfBorn' => $year, 'yearOfDeath' => $year + 98, 'sex' => false, 'desireChild' => $firstWomanChildrenDesire->toArray()]);

        while($firstWoman->isAlive($year)) {

            if ($firstWoman->desireChild->wishToBornNow($firstWoman->age($year))) {
                $sender->send($this->newChildEvent($year, $firstWoman));
            }

            $year++;
        }
    }

    protected function randomSex(): bool
    {
        return (bool)mt_rand(0, 1);
    }

    protected function newChildEvent(int $year, Humanoid $mother): BirthEvent
    {
        $event = new BirthEvent();
        $event->yearOfBorn = $year;
        $event->name = 'Name-' . ++$this->fastName;
        $event->sex = $this->randomSex();
        $event->motherName = $mother->name;

        $mother->desireChild->bornChild($mother->age($year));

        return $event;
    }
}
