<?php

/** @var \App\Models\Person\Person $model */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year ?? 0;

$fBegin = new \App\Dto\Form\FormFieldInputDto();
$fBegin->id = 'begin';
$fBegin->type = 'number';
$fBegin->value = old($fBegin->id) ?? $model->lives->last()?->end;
$fBegin->label = 'the year of Beginning of Life';
$fEnd = new \App\Dto\Form\FormFieldInputDto();
$fEnd->id = 'end';
$fEnd->type = 'number';
$fEnd->label = 'Death, the End';
$fType = new \App\Dto\Form\FormFieldInputDto();
$fType->id = 'type';
$fType->label = 'What is the Life?';
$fType->value = old($fType->id) ?? ($model->lives->last()?->type_id == 2 ? 1 : 2);
$fType->options = \App\Models\World\LifeType::selectOptions();
$fRole = new \App\Dto\Form\FormFieldInputDto();
$fRole->id = 'role';
$fRole->label = 'Who was the Persona?';
$fRole->value = old($fRole->id) ?? ($fType->value == 2 ? 1 : 3);
$fRole->options = \App\Models\World\Life::selectRoleOptions();

$lifeBacked = [1 => 'primary', 2 => 'success', 3 => 'danger', 4 => 'warning'];

?><x-layout.main>
    <x-layout.header-main>{{$model->name}}</x-layout.header-main>

    <x-form.basic :route="route('web.person.add')"
                  btn="show Year"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    <x-layout.container>
        <div class="list-group">
            @foreach($model->lives as $life)
                @php($backClass = $lifeBacked[$life->type_id] ?? 'light')
                <a href="#" class="list-group-item list-group-item-action list-group-item-{{$backClass}}">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$life->type->name}}</h5>
                        <small>{{$life->end - $life->begin}} years</small>
                    </div>
                    <p class="mb-1">Years {{$life->begin}}-{{$life->end}} {{$life->role_name}}</p>
                    <small>now he is 25</small>
                </a>
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.header-second>Work with Life</x-layout.header-second>

    <x-form.basic :route="route('web.person.add-life', ['id' => $model->id])"
                  btn="add Life"
                  :fields="[$fBegin, $fEnd, $fType, $fRole]"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
        <a href="{{route('web.person.form')}}" type="button" class="btn btn-success btn-lg">new Persona</a>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
    </x-form.container>

{{--    <div style="height: 100px;"></div>--}}

</x-layout.main>
