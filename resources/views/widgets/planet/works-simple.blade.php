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
$fWorkConsumers = new \App\Dto\Form\FormFieldInputDto();
$fWorkConsumers->id = 'consumers';
$fWorkConsumers->type = 'number';
$fWorkConsumers->label = 'how many consumed Work';

?>
<x-layout.header-second>Work</x-layout.header-second>
<x-form.container>
    @foreach(\App\Models\Work\Work::selectOptions() as $workOpt)
        <span class="badge text-bg-secondary">{{ $workOpt['lbl'] }}</span>
    @endforeach
</x-form.container>
<x-form.basic :route="route('web.basic.work')"
              btn="add new Work"
              :fields="[$fWorkName, $fWorkBegin, $fWorkEnd, $fWorkCapacity, $fWorkConsumers]"></x-form.basic>
