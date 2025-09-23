<?php

/** @var \App\Models\Work\Work $model */

$factory = new \App\Dto\Form\FormInputFactory();

$fYear = $factory->number('year', 'Year of current moment', $factory->withValue($year > 0 ? $year : null));

$basicWorkEdit = [
    $factory->input('name', $factory->withValue(old('name') ?? $model->name)),
    $factory->number('capacity', 'maximum Work units', $factory->withValue(old('capacity') ?? $model->capacity)),
    $factory->number('consumers', 'how many consumed Work', $factory->withValue(old('consumers') ?? $model->consumers)),
];

?><x-layout.main :title="'Work ' . $model->name">
    <x-layout.header-main>
        Work [{{ $model->begin }}-{{ $model->end }}]Y
        @if($model->consumers) for {{ $model->consumers }} @endif
        💪🏻 {{ $model->calculations->workYears }}
    </x-layout.header-main>

    <x-layout.container>
        @include('widgets.person.events', ['year' => $year, 'events' => \App\Models\Collection\PersonEventCollection::toCollection($model->events)->sortVsBegin()])
    </x-layout.container>

    @if($model->consumers)
        <x-layout.header-second>CONSUMING: each 💪🏻 {{ $model->consuming_of_person }} days-vs-Year {{ (int)$model->consuming_days_per_year }} 🛍️</x-layout.header-second>
    @endif

    <x-layout.header-second>workers</x-layout.header-second>

    <x-form.basic :route="route('web.basic.works-details', ['id' => $model->id])"
                  btn="show Year"
                  :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.basic.works-details', ['id' => $model->id])] : null"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    <x-layout.container>
        <div class="list-group">
            @foreach($model->calculations->workers as $personDto)
                @include('widgets.person.list-item', ['person' => $personDto->person, 'year' => $year, 'showInfo' => $model->percentByDays($personDto->days) . '%'])
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.header-second>edit Work</x-layout.header-second>

    <x-form.basic :route="route('web.basic.work-edit', ['id' => $model->id])"
                  btn="Change Work"
                  :fields="$basicWorkEdit"></x-form.basic>

    <x-layout.divider />

    <x-layout.container>
        <x-pages.major-nav />
        <x-button.link :cc="CC_DANGER" :route="route('web.basic.work-correct', ['id' => $model->id])" label="Correct me"/>
    </x-layout.container>

</x-layout.main>
