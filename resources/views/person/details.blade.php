<?php

/** @var \App\Models\Person\Person $model */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year ?? 0;

$fName = new \App\Dto\Form\FormFieldInputDto();
$fName->id = 'name';
$fName->label = 'Full Name';

$fBegin = new \App\Dto\Form\FormFieldInputDto();
$fBegin->id = 'begin';
$fBegin->type = 'number';
$fBegin->value = old($fBegin->id) ?? $model->lives->last()?->end ?? $model->begin;
$fBegin->label = 'the year of Beginning of Life';
$fEnd = new \App\Dto\Form\FormFieldInputDto();
$fEnd->id = 'end';
$fEnd->type = 'number';
$fEnd->label = 'Death, the End';
$fType = new \App\Dto\Form\FormFieldInputDto();
$fType->id = 'type';
$fType->label = 'What is the Life?';
$fType->value = old($fType->id) ?? ($model->lives->last()?->type_id == \App\Models\World\LifeType::PLANET ? \App\Models\World\LifeType::ALLODS : \App\Models\World\LifeType::PLANET);
$fType->options = \App\Models\World\LifeType::selectOptions();
$fRole = new \App\Dto\Form\FormFieldInputDto();
$fRole->id = 'role';
$fRole->label = 'Who was the Persona?';
$fRole->value = old($fRole->id) ?? ($fType->value == \App\Models\World\LifeType::PLANET ? \App\Models\World\Life::MAN : \App\Models\World\Life::SPIRIT);
$fRole->options = \App\Models\World\Life::selectRoleOptions();
$fParents = new \App\Dto\Form\FormFieldInputDto();
$fParents->id = 'parents';
$fParents->label = 'What kind of Parents?';
$fParents->options = \App\Models\Person\ParentsType::selectOptions();
$fFather = new \App\Dto\Form\FormFieldInputDto();
$fFather->id = 'father';
$fFather->label = 'the Name of the Father';
$fMother = new \App\Dto\Form\FormFieldInputDto();
$fMother->id = 'mother';
$fMother->label = 'the Name of the Mother';

$lifeBacked = [1 => 'primary', 2 => 'success', 3 => 'danger', 4 => 'warning'];

$vPerson = new \App\Models\Person\PersonView();

?><x-layout.main>
    <x-layout.header-main>{{$model->name}}</x-layout.header-main>

    <x-form.basic :route="route('web.planet.params')"
                  btn="show Year"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    @if($model->force_person == 100 && $model->lives->last()?->type_id == \App\Models\World\LifeType::ALLODS)
        <x-form.basic :route="route('web.person.add', ['author_id' => $model->id])"
                      btn="create Persona"
                      :fields="[$fName, $fBegin]"></x-form.basic>

        <div class="mb-5 mt-5"></div>
    @endif

    <x-layout.container>
        <div class="list-group">
            @foreach($model->lives as $life)
                @php($backClass = $lifeBacked[$life->type_id] ?? 'light')
                <a href="#" class="list-group-item list-group-item-action list-group-item-{{$backClass}}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$life->type->name}}</h5>
                        <small>
                            @if($life->begin_force_person == 100)<span class="badge text-bg-success">Can create Life</span>@endif
                            @if($life->begin_force_woman == 100)<span class="badge text-bg-warning">May be a Girl</span>@endif
                            <span class="badge text-bg-secondary">{{$life->end - $life->begin}} years</span>
                        </small>
                    </div>
                    <p class="mb-1">Years {{$life->begin}}-{{$life->end}} {{$life->role_name}}</p>
                    <small>{{ $vPerson->labelForce($life) }}</small>
                </a>
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.header-second>Scripting Life at year {{$model->lives->last()?->end ?? $model->begin}}</x-layout.header-second>

    <x-form.basic :route="route('web.person.add-life', ['id' => $model->id])"
                  btn="add Life"
                  :fields="[$fBegin, $fEnd, $fType, $fRole, $fParents, $fFather, $fMother]"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
{{--        <a href="{{route('web.person.form')}}" type="button" class="btn btn-success btn-lg">new Persona</a>--}}
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
    </x-form.container>

{{--    <div style="height: 100px;"></div>--}}

</x-layout.main>
