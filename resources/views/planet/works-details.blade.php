<?php

/** @var \App\Models\Work\Work $model */

$fWorkName = new \App\Dto\Form\FormFieldInputDto();
$fWorkName->id = 'name';
$fWorkName->value = old($fWorkName->id) ?? $model->name;
$fWorkName->label = 'Name';
$fWorkCapacity = new \App\Dto\Form\FormFieldInputDto();
$fWorkCapacity->id = 'capacity';
$fWorkCapacity->type = 'number';
$fWorkCapacity->value = old($fWorkCapacity->id) ?? $model->capacity;
$fWorkCapacity->label = 'maximum Work units';
$fWorkConsumers = new \App\Dto\Form\FormFieldInputDto();
$fWorkConsumers->id = 'consumers';
$fWorkConsumers->type = 'number';
$fWorkConsumers->value = old($fWorkConsumers->id) ?? $model->consumers;
$fWorkConsumers->label = 'how many consumed Work';

?><x-layout.main :title="'Work ' . $model->name">
    <x-layout.header-main>
        Work [{{ $model->begin }}-{{ $model->end }}]Y
        @if($model->consumers) for {{ $model->consumers }} @endif
        💪🏻 {{ $model->calculations->workYears }}
    </x-layout.header-main>

    <x-layout.container>
        @include('widgets.person.events', ['events' => \App\Models\Collection\PersonEventCollection::toCollection($model->events)->sortVsBegin()])
    </x-layout.container>

    @if($model->consumers)
        <x-layout.header-second>CONSUMING: each 💪🏻 {{ $model->consuming_of_person }} days-vs-Year {{ (int)$model->consuming_days_per_year }} 🛍️</x-layout.header-second>
    @endif

    <x-layout.header-second>edit Work</x-layout.header-second>

    <x-form.basic :route="route('web.basic.work-edit', ['id' => $model->id])"
                  btn="Change Work"
                  :fields="[$fWorkName, $fWorkCapacity, $fWorkConsumers]"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{route('web.planet.works-list')}}" type="button" class="btn btn-primary btn-lg">Work</a>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
        <a href="{{route('web.basic.work-correct', ['id' => $model->id])}}" type="button" class="btn btn-danger btn-lg">Correct me</a>
    </x-form.container>

</x-layout.main>
