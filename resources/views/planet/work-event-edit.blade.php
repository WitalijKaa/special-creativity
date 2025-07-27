<?php

/** @var \App\Models\Person\PersonEvent $model */

$fBegin = new \App\Dto\Form\FormFieldInputDto();
$fBegin->id = 'begin';
$fBegin->type = 'number';
$fBegin->value = old($fBegin->id) ?? $model->begin;
$fBegin->label = 'start of Event';
$fEnd = new \App\Dto\Form\FormFieldInputDto();
$fEnd->id = 'end';
$fEnd->type = 'number';
$fEnd->value = old($fEnd->id) ?? $model->end;
$fEnd->label = 'finish of Event';
$fStrong = new \App\Dto\Form\FormFieldInputDto();
$fStrong->id = 'strong';
$fStrong->type = 'number';
$fStrong->value = old($fStrong->id) ?? $model->strong;
$fStrong->label = 'how strong % worked?';
$fComment = new \App\Dto\Form\FormFieldInputDto();
$fComment->id = 'comment';
$fComment->type = 'textarea';
$fComment->value = old($fComment->id) ?? $model->comment;
$fComment->label = 'more Description of Event';

?><x-layout.main :title="'Work Event ' . $model->work->name">
    <x-layout.header-main>{{ $model->work->name }} Work Event [{{ $model->begin }}-{{ $model->end }}]Y</x-layout.header-main>

    <x-form.basic :route="route('web.basic.event-edit', ['id' => $model->id])"
                  btn="Change Work"
                  :fields="[$fBegin, $fEnd, $fStrong, $fComment]"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.major-nav')
    </x-form.container>

</x-layout.main>
