<?php

/** @var \App\Models\Person\PersonEvent $model */

$factory = new \App\Dto\Form\FormInputFactory();

$basicEventEdit = [
    $factory->number('begin', $factory->withValue(old('begin') ?? $model->begin)),
    $factory->number('end', $factory->withValue(old('end') ?? $model->end)),
    $factory->number('strong', 'how strong % worked?', $factory->withValue(old('strong') ?? $model->strong)),
    $factory->textarea('comment', $factory->withValue(old('comment') ?? $model->comment)),
];

?><x-layout.main :title="'Work Event ' . $model->work->name">
    <x-layout.header-main>{{ $model->work->name }} Work Event [{{ $model->begin }}-{{ $model->end }}]Y</x-layout.header-main>

    <x-form.basic :route="route('web.basic.event-edit', ['id' => $model->id])"
                  btn="Change Work"
                  :fields="$basicEventEdit"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.major-nav')
    </x-form.container>

</x-layout.main>
