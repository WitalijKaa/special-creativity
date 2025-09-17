<?php

/** @var array $years */
/** @var int $begin */
/** @var int $end */
/** @var int $count */
/** @var \App\Models\World\Life $life */
/** @var \App\Models\Collection\LifeCollection $allodsLives */
/** @var \App\Models\Collection\LifeCollection $planetLives */

$factory = new \App\Dto\Form\FormInputFactory();

$visualYearsPopulation = [
    $factory->number('begin', 'from Year', $factory->withValue(old('begin') ?? $begin)),
    $factory->number('end', 'until Year', $factory->withValue(old('end') ?? $end))
];

$vPerson = new \App\Models\View\PersonView();
$summaryAgesMinimum = 4;

$titlePage = $count . ' Lives-Years ' . $begin . '-' . $end . 'Y';

const TOTAL = 0;
const AGES = [
    ['Babies' => [0, 6], 'Children 7-10' => [7, 10], 'Young' => [11, 14], 'Teens 15-19' => [15, 19]],
    ['Adults' => [20, 55], 'Age 20-28' => [20, 28], 'Middle age' => [29, 36], 'Age 37-55' => [37, 55]],
    ['OldAge' => [56, 222], 'Old 56-64' => [56, 64], 'Old 65+' => [65, 222], 'Total' => [0, 222]],
];

$calcSummaryAges = function (int $Y, \App\Models\Collection\LifeCollection $planetLives) {
    $summaryAges = [[], [], []];

    foreach (AGES as $ix => $ageDiv) {
        foreach ($ageDiv as $ageName => $ageRange) {
            $fromAge = $ageRange[0];
            $untilAge = $ageRange[1];

            $summaryAges[$ix][$ageName] = [
                TOTAL => $planetLives->countAtAgeRange($Y, $fromAge, $untilAge),
                \App\Models\World\Life::MAN => $planetLives->countAtAgeRangeGender(\App\Models\World\Life::MAN, $Y, $fromAge, $untilAge),
                \App\Models\World\Life::WOMAN => $planetLives->countAtAgeRangeGender(\App\Models\World\Life::WOMAN, $Y, $fromAge, $untilAge),
            ];
        }
    }

    return $summaryAges;
}

?><x-layout.main :title="$titlePage">
    <x-layout.header-main>{{ $titlePage }}</x-layout.header-main>

    <x-form.basic :route="route('web.visual.years-population')"
                  btn="show Range"
                  :btn-warn="['lbl' => 'Back', 'href' => route('web.visual.years-population')]"
                  :fields="$visualYearsPopulation"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-layout.container>
        @foreach($years as $Y => $livesCollections)
            @php($allodsAgeShow = [])
            @php($planetAgeShow = [])
            @php($allodsLives = $livesCollections[\App\Models\World\Life::ALLODS])
            @php($planetLives = $livesCollections[\App\Models\World\Life::PLANET])

            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $Y }}Y
                    <div>
                        <span class="badge bg-dark rounded-pill">{{ $allodsLives->count() + $planetLives->count() }}</span>
                        @if($allodsLives->count())
                            <span class="badge bg-primary rounded-pill">{{ $allodsLives->count() }}</span>
                        @endif
                        @if($planetLives->count())
                            <span class="badge bg-success rounded-pill">{{ $planetLives->count() }}</span>
                        @endif
                    </div>
                </li>

                @php($summaryAges = $calcSummaryAges($Y, $planetLives))

                @foreach($summaryAges as $ageDiv)
                    <li class="list-group-item list-group-item-warning d-flex justify-content-between align-items-center">
                        @foreach($ageDiv as $ageName => $ageResult)
                            <div>
                                {{ $ageName }}
                                @if($ageResult[TOTAL])
                                    <span class="badge bg-dark rounded-pill">{{ $ageResult[TOTAL] }}</span>
                                    @if($ageResult[\App\Models\World\Life::MAN] && $ageResult[TOTAL] != $ageResult[\App\Models\World\Life::MAN])
                                        <span class="badge bg-info rounded-pill">{{ $ageResult[\App\Models\World\Life::MAN] }}</span>
                                    @endif
                                    @if($ageResult[\App\Models\World\Life::WOMAN])
                                        <span class="badge bg-danger rounded-pill">{{ $ageResult[\App\Models\World\Life::WOMAN] }}</span>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </li>
                @endforeach

                @foreach($allodsLives as $life)
                    @php($age = $Y - $life->begin + 1)
                    @if(!in_array($age, $allodsAgeShow))
                        <li class="list-group-item list-group-item-primary d-flex justify-content-between align-items-center">
                            {!! $vPerson->labelPersonOfYear($life, $Y, $allodsLives) !!}
                        </li>
                        @php($allodsAgeShow[] = $age)
                    @endif
                @endforeach
                @foreach($planetLives as $life)
                    @php($age = $Y - $life->begin + 1)
                    @if(!in_array($age, $planetAgeShow))
                        <li class="list-group-item list-group-item-success d-flex justify-content-between align-items-center">
                            {!! $vPerson->labelPersonOfYear($life, $Y, $planetLives) !!}
                        </li>
                        @php($planetAgeShow[] = $age)
                    @endif
                @endforeach
            </ul>
            <br><br>
        @endforeach
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.persons-list-nav')
    </x-form.container>

</x-layout.main>

