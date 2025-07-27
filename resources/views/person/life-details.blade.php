<?php

/** @var \App\Models\World\Life $model */
/** @var \Illuminate\Support\Collection|\App\Models\World\Life[] $connections */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Support\Collection|\App\Models\Work\Work[] $work */

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
$fWork = new \App\Dto\Form\FormFieldInputDto();
$fWork->id = 'work';
$fWork->label = 'Worked at';
$fWork->options = \App\Models\Work\Work::selectSpecificOptions($work);
$fStrong = new \App\Dto\Form\FormFieldInputDto();
$fStrong->id = 'strong';
$fStrong->type = 'number';
$fStrong->label = 'how strong % worked?';
$fComment = new \App\Dto\Form\FormFieldInputDto();
$fComment->id = 'comment';
$fComment->type = 'textarea';
$fComment->label = 'more Description of Event';

$formFields = [$fBegin, $fEnd, $fTypes, $fWork, $fStrong, $fComment];
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
    <x-layout.header-main>
        {{ $model->person->name }} {{ $model->person->nick }} {{ $model->role_name }}
        <br>
        [{{ $model->begin }}-{{ $model->end }}]Y<small><small>{{ $model->end - $model->begin }}</small></small>
        <br>
        {{ $model->type_name }}-{{ $model->current_type_no }}
    </x-layout.header-main>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'person' => $model->person, 'lifeWork' => $model->lifeWork])
    </x-layout.container>

    <x-layout.header-second>Work 💪🏻 is {{ $model->lifeWork->workYears }}</x-layout.header-second>

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $model])
    </x-form.container>

    <x-layout.header-second>edit Events of this Life</x-layout.header-second>

    <x-form.basic :route="route('web.person.add-event', ['id' => $model->id])"
                  btn="add Event"
                  :fields="$formFields"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $model])
    </x-form.container>

</x-layout.main>
