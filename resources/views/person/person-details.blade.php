<?php

/** @var int $year */
/** @var \App\Models\Person\Person $model */
$viewLives = $year > 0 ? $model->lives->filter(fn(\App\Models\World\Life $life) => $life->begin <= $year)->values() : $model->lives;
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[]|null $eventsFuture */

$factory = new \App\Dto\Form\FormInputFactory();

$fYear = $factory->number('year', 'Year of current moment', $factory->withValue($year > 0 ? $year : null));
$fBegin = $factory->number('begin', 'the year of Beginning of Existence', $factory->withValue(old('begin') ?? $model->lives->last()?->end ?? $model->begin));

$personAdd = [
    $factory->input('name', 'Full Name'),
    $factory->input('nick', 'Nick-Name'),
    $fBegin,
];


$nextLifeType = old('type') ?? (((!$model->lives->count() && $model->id != App\Models\Person\Person::ORIGINAL) || $model->lives->last()?->is_allods) ? \App\Models\World\Life::PLANET : \App\Models\World\Life::ALLODS);
$addLifeForm = [
    $fBegin,
    $factory->number('end', 'Death, the End'),
    $factory->select('type', \App\Models\World\Life::selectTypeOptions(), 'What is the Life?', $factory->withValue($nextLifeType)),
    $factory->select('role', \App\Models\World\Life::selectRoleOptions(), 'Who was the Persona?', $factory->withValue(old('role') ?? ($nextLifeType == \App\Models\World\Life::PLANET ? \App\Models\World\Life::MAN : \App\Models\World\Life::SPIRIT))),
];

$vPerson = new \App\Models\View\PersonView();

$titlePage = $model->name . ' ' . $model->nick;
$titlePage .= $year > 0 ? ' ' . $year . 'Y' : '';

?>
<x-layout.main :title="$titlePage">
    <x-layout.header-main>{{$titlePage}}</x-layout.header-main>

    <x-layout.container>
        <div class="list-group">
            @foreach($viewLives as $life)
                @include('widgets.life.list-item', ['model' => $life])
            @endforeach
        </div>
    </x-layout.container>

    @if($model->force_person == \App\Models\Person\Person::FORCE && $model->lives->last()?->is_allods)
        <x-layout.header-second>a new Persona may be created</x-layout.header-second>

        <x-form.basic :route="route('web.person.add', ['author_id' => $model->id])"
                      btn="create Persona"
                      :fields="$personAdd"></x-form.basic>
        <x-layout.divider />
    @endif

    @if($model->lives->count() > 4)
        <x-layout.divider />

        <x-form.basic :route="route('web.person.details', ['id' => $model->id])"
                      btn="show Year"
                      :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.person.details', ['id' => $model->id])] : null"
                      :fields="[$fYear]"></x-form.basic>

        <x-layout.divider />

        <x-layout.container>
            <x-pages.person-details-nav :model="$model" />
            <x-pages.major-nav />
        </x-layout.container>
    @endif

    @if($model->lives->count())
        <x-layout.container>
            <x-layout.header-second>some Events during lives path</x-layout.header-second>

            @include('widgets.person.events', ['events' => $events, 'person' => $model])
            @if($eventsFuture)
                <div class="mb-5 mt-5"></div>
                @include('widgets.person.events', ['events' => $eventsFuture, 'person' => $model])
            @endif
        </x-layout.container>
    @endif

    @if($year < 1)

        <x-layout.header-second>Scripting Life at year {{$model->lives->last()?->end ?? $model->begin}}</x-layout.header-second>

        @php($fBegin->label = 'the year of Beginning of Life')
        <x-form.basic :route="route('web.person.add-life', ['id' => $model->id])"
                      btn="add Life"
                      :fields="$addLifeForm"></x-form.basic>

    @endif

    <x-layout.divider />

    <x-layout.container>
        <x-pages.person-details-nav :model="$model" />
        <x-pages.major-nav />
    </x-layout.container>

</x-layout.main>
