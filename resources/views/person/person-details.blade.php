<?php

/** @var int $year */
/** @var \App\Models\Person\Person $model */
$viewLives = $year > 0 ? $model->lives->filter(fn (\App\Models\World\Life $life) => $life->begin <= $year)->values() : $model->lives;
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[]|null $eventsFuture */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year > 0 ? $year : null;

$fName = new \App\Dto\Form\FormFieldInputDto();
$fName->id = 'name';
$fName->label = 'Full Name';
$fNick = new \App\Dto\Form\FormFieldInputDto();
$fNick->id = 'nick';
$fNick->label = 'Nick-Name';

$fBegin = new \App\Dto\Form\FormFieldInputDto();
$fBegin->id = 'begin';
$fBegin->type = 'number';
$fBegin->value = old($fBegin->id) ?? $model->lives->last()?->end ?? $model->begin;
$fBegin->label = 'the year of Beginning of Existence';
$fEnd = new \App\Dto\Form\FormFieldInputDto();
$fEnd->id = 'end';
$fEnd->type = 'number';
$fEnd->label = 'Death, the End';
$fType = new \App\Dto\Form\FormFieldInputDto();
$fType->id = 'type';
$fType->label = 'What is the Life?';
$fType->value = old($fType->id) ?? (((!$model->lives->count() && $model->id != App\Models\Person\Person::ORIGINAL) || $model->lives->last()?->is_allods) ? \App\Models\World\Life::PLANET : \App\Models\World\Life::ALLODS);
$fType->options = \App\Models\World\Life::selectTypeOptions();
$fRole = new \App\Dto\Form\FormFieldInputDto();
$fRole->id = 'role';
$fRole->label = 'Who was the Persona?';
$fRole->value = old($fRole->id) ?? ($fType->value == \App\Models\World\Life::PLANET ? \App\Models\World\Life::MAN : \App\Models\World\Life::SPIRIT);
$fRole->options = \App\Models\World\Life::selectRoleOptions();
$fFather = new \App\Dto\Form\FormFieldInputDto();
$fFather->id = 'father';
$fFather->label = 'the Name of the Father';
$fMother = new \App\Dto\Form\FormFieldInputDto();
$fMother->id = 'mother';
$fMother->label = 'the Name of the Mother';

$vPerson = new \App\Models\View\PersonView();

$titlePage = $model->name . ' ' . $model->nick;
$titlePage .= $year > 0 ? ' ' . $year . 'Y' : '';

?>
<x-layout.main :title="$titlePage">
    <x-layout.header-main>{{$titlePage}}</x-layout.header-main>

    @if($model->force_person == \App\Models\Person\Person::FORCE && $model->lives->last()?->is_allods)
        <x-layout.header-second>a new Persona may be created</x-layout.header-second>

        <x-form.basic :route="route('web.person.add', ['author_id' => $model->id])"
                      btn="create Persona"
                      :fields="[$fName, $fNick, $fBegin]"></x-form.basic>

        <x-layout.divider></x-layout.divider>
    @endif

    <x-form.basic :route="route('web.person.details', ['id' => $model->id])"
                  btn="show Year"
                  :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.person.details', ['id' => $model->id])] : null"
                  :fields="[$fYear]"></x-form.basic>

    <x-form.container>
        @include('components.pages.person-details-nav', ['$model' => $model])
    </x-form.container>

    <x-layout.container>
        <div class="list-group">
            @foreach($viewLives as $life)
                @include('widgets.life.list-item', ['model' => $life])
            @endforeach
        </div>
    </x-layout.container>

    <x-form.container>
        @include('components.pages.person-details-nav', ['$model' => $model])
    </x-form.container>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'person' => $model])
        @if($eventsFuture)
            <div class="mb-5 mt-5"></div>
            @include('widgets.person.events', ['events' => $eventsFuture, 'person' => $model])
        @endif
    </x-layout.container>


    @if($year < 1)

        <x-layout.header-second>Scripting Life at year {{$model->lives->last()?->end ?? $model->begin}}</x-layout.header-second>

        @php($fBegin->label = 'the year of Beginning of Life')
        <x-form.basic :route="route('web.person.add-life', ['id' => $model->id])"
                      btn="add Life"
                      :fields="[$fBegin, $fEnd, $fType, $fRole, $fFather, $fMother]"></x-form.basic>

    @endif

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.person-details-nav', ['$model' => $model])
    </x-form.container>

</x-layout.main>
