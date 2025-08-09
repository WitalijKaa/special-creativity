<?php

namespace App\Http\Controllers\Person\Visual;

use App\Models\Collection\LifeCollection;
use App\Models\World\Life;
use Illuminate\Foundation\Http\FormRequest;

class YearsPopulationAction
{
    public function __invoke(FormRequest $request)
    {
        $begin = (int)$request->get('begin', 220);
        $end = (int)$request->get('end', 400);
        $end = $end > $begin ? $end : $begin + 1;

        $livesAllods = LifeCollection::allodsByRange($begin, $end)->sortBy('id')->values();
        $livesPlanet = LifeCollection::planetByRange($begin, $end)->sortBy('id')->values();
        $count = $livesAllods->count() + $livesPlanet->count();
        $years = [];

        $Y = $begin;
        while ($Y <= $end) {
            $years[$Y] = [
                Life::ALLODS => $this->filter($livesAllods, $Y),
                Life::PLANET => $this->filter($livesPlanet, $Y),
            ];

            $Y++;
        }

        return view('person.visual.years-population', compact('count', 'begin', 'end', 'years'));
    }

    private function filter(LifeCollection $lives, int $year): LifeCollection
    {
        $return = new LifeCollection();

        foreach ($lives as $life) {
            /** @var $life Life */

            if ($life->begin > $year ||
                ($life->end - 1) < $year)
            {
                continue;
            }

            $return->push($life);
        }

        return $return->sortByBegin();
    }
}
