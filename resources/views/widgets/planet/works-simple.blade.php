<?php

$fWorkName = new \App\Dto\Form\FormFieldInputDto();
$fWorkName->id = 'name';
$fWorkName->label = 'Name';
$fWorkBegin = new \App\Dto\Form\FormFieldInputDto();
$fWorkBegin->id = 'begin';
$fWorkBegin->type = 'number';
$fWorkBegin->label = 'Started at';
$fWorkEnd = new \App\Dto\Form\FormFieldInputDto();
$fWorkEnd->id = 'end';
$fWorkEnd->type = 'number';
$fWorkEnd->label = 'Finished at';
$fWorkCapacity = new \App\Dto\Form\FormFieldInputDto();
$fWorkCapacity->id = 'capacity';
$fWorkCapacity->type = 'number';
$fWorkCapacity->label = 'maximum Work units';

?><x-layout.header-second>Work</x-layout.header-second>
<x-form.container>
    @foreach(\App\Models\World\Work::selectOptions() as $workOpt)
        <span class="badge text-bg-secondary">{{ $workOpt['lbl'] }}</span>
    @endforeach
</x-form.container>
<x-form.basic :route="route('web.basic.work')"
              btn="add new Work"
              :fields="[$fWorkName, $fWorkBegin, $fWorkEnd, $fWorkCapacity]"></x-form.basic>
