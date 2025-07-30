<?php

/** @var int $year */
/** @var array|\App\Models\World\Prediction\PredictionPeriodPersonsDto[] $growPercentPrediction */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year > 0 ? $year : null;
$fPeriod = new \App\Dto\Form\FormFieldInputDto();
$fPeriod->id = 'period';
$fPeriod->label = 'Calculations period';
$fPeriod->type = 'number';
$fPeriod->value = $period > 0 ? $period : null;

?><x-layout.main title="Future">
    <x-layout.header-main>Future Simple</x-layout.header-main>

    <x-form.basic :route="route('web.prediction.future')"
                  btn="make Prediction"
                  :fields="[$fYear, $fPeriod]"></x-form.basic>

    <x-form.container>
        @include('components.pages.major-nav')
    </x-form.container>

    @if($growPercentPrediction)
        <x-layout.header-second>Prediction persons grow</x-layout.header-second>
        <x-layout.container>
            <ul class="list-group list-group-flush">
                @foreach($growPercentPrediction as $item)
                    <li class="list-group-item">at Year <strong>{{ $item->begin }}</strong> there were <strong>{{ number_format($item->persons, 0, '', ' ') }}</strong></li>
                @endforeach
                <li class="list-group-item">at Year <strong>{{ $item->end }}</strong> there were <strong>{{ number_format($item->persons + $item->created, 0, '', ' ') }}</strong></li>
            </ul>
        </x-layout.container>
    @endif

</x-layout.main>
