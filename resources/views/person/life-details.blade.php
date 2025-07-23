<?php

/** @var \App\Models\World\Life $model */
/** @var \Illuminate\Support\Collection|\App\Models\World\Life[] $connections */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */

$fBegin = new \App\Dto\Form\FormFieldInputDto();
$fBegin->id = 'begin';
$fBegin->type = 'number';
$fBegin->value = old($fBegin->id) ?? ($model->begin + 10);
$fBegin->label = 'start of Event';
$fEnd = new \App\Dto\Form\FormFieldInputDto();
$fEnd->id = 'end';
$fEnd->type = 'number';
$fEnd->value = old($fEnd->id) ?? ($model->begin + 50);
$fEnd->label = 'finish of Event';
$fTypes = new \App\Dto\Form\FormFieldInputDto();
$fTypes->id = 'type';
$fTypes->label = 'What was It?';
$fTypes->options = \App\Models\Person\EventType::selectOptions();
$fComment = new \App\Dto\Form\FormFieldInputDto();
$fComment->id = 'comment';
$fComment->type = 'textarea';
$fComment->label = 'more Description of Event';

$formFields = [$fBegin, $fEnd, $fTypes, $fComment];
for ($ix = 1; $ix <= 13; $ix++) {
    $fConnect = new \App\Dto\Form\FormFieldInputDto();
    $fConnect->id = 'connect_' . $ix;
    $fConnect->label = 'With who?';
    $fConnect->options = \App\Models\World\Life::selectConnectionOptions($connections);
    $formFields[] = $fConnect;

    if ($ix >= $connections->count()) {
        break;
    }
}

?><x-layout.main :title="$model->person->name . ' ' . $model->type_name . '-' . $model->current_type_no">
    <x-layout.header-main>{{$model->person->name}} {{$model->person->nick}} {{$model->role_name}} {{$model->begin}}-{{$model->end}}</x-layout.header-main>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'personID' => $model->person_id])
    </x-layout.container>

    <x-layout.header-second>work with Events of this Life</x-layout.header-second>

    <x-form.basic :route="route('web.person.add-event', ['id' => $model->id])"
                  btn="add Event"
                  :fields="$formFields"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.person.details', ['id' => $model->person->id])}}" type="button" class="btn btn-success btn-lg">{{$model->person->name}}</a>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
    </x-form.container>

</x-layout.main>
