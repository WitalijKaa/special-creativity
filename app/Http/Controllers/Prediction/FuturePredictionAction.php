<?php

namespace App\Http\Controllers\Prediction;

use App\Models\Person\Person;
use App\Models\World\Prediction\PredictionPeriodPersonsDto;
use Illuminate\Foundation\Http\FormRequest;

class FuturePredictionAction
{
    private const FINAL_YEAR = 10000;
    private const FINAL_PERSONS = 4000000;

    public function __invoke(FormRequest $request)
    {
        $year = (int)$request->get('year', 0);
        $period = (int)$request->get('period', 0);

        $growPercentPrediction = $this->growPercentPrediction($year, $period);

        return view('prediction.future-simple', compact('year', 'period', 'growPercentPrediction'));
    }

    private function growPercentPrediction(int $year, int $period): array
    {
        if ($year < 1 || $period < 10) {
            return [];
        }

        $grow = [];

        $doNext = true;
        $prevYear = $year;
        $year += $period;
        while ($doNext) {
            $item = new PredictionPeriodPersonsDto(begin: $prevYear, end: $year);

            $item->persons = Person::where('begin', '<', $prevYear)
                ->count();
            $item->created = Person::where('begin', '>=', $prevYear)
                ->where('begin', '<', $year)
                ->count();

            $doNext = !!$item->created;

            if ($doNext && count($grow)) {
                $prevItem = $grow[count($grow) - 1];
                $item->calculation = $item->created / $prevItem->persons * 100;
            }

            $grow[] = $item;
            $prevYear = $year;
            $year += $period;
        }

        array_shift($grow);
        array_pop($grow);

        if (count($grow) < 4) {
            return [];
        }

        $percent = 0.0;
        foreach ($grow as $item) {
            $percent += $item->calculation;
        }
        $percent = $percent / count($grow);

        $predictionYear = $grow[count($grow) - 1]->end;
        $persons = Person::where('begin', '<', $predictionYear)->count();

        $growPredict = [];

        while ($predictionYear < self::FINAL_YEAR && $persons < self::FINAL_PERSONS) {
            $created = (int)round($persons / 100 * $percent, 8);

            $item = new PredictionPeriodPersonsDto(begin: $predictionYear, end: $predictionYear + $period);
            $item->persons = $persons;
            $item->created = $created;
            $growPredict[] = $item;

            $predictionYear += $period;
            $persons += $created;
        }

        return $growPredict;
    }
}
