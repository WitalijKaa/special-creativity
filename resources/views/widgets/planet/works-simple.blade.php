<?php

$factory = new \App\Dto\Form\FormInputFactory();

$basicWork = [
    $factory->input('name'),
    $factory->number('begin', 'Started at'),
    $factory->number('end', 'Finished at'),
    $factory->number('capacity', 'maximum Work units'),
    $factory->number('consumers', 'how many consumed Work'),
];

?>
<x-layout.header-second>Work</x-layout.header-second>
<x-layout.container>
    @foreach(\App\Models\Work\Work::selectOptions() as $workOpt)
        <span class="badge text-bg-secondary">{{ $workOpt['lbl'] }}</span>
    @endforeach
</x-layout.container>
<x-form.basic :route="route('web.basic.work')"
              btn="add new Work"
              :fields="$basicWork"></x-form.basic>
