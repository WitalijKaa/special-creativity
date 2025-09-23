<?php

$factory = new \App\Dto\Form\FormInputFactory();

$workForm = [
    $factory->input('name'),
    $factory->number('begin'),
    $factory->number('end'),
    $factory->number('capacity', 'maximum Work units'),
    $factory->number('consumers', 'how many consumed Work'),
];

?><x-layout.main title="Work">
    <x-layout.header-main>add Work type</x-layout.header-main>

    <x-form.basic :route="route('web.basic.work-add')"
                  btn="add new Work"
                  :fields="$workForm"></x-form.basic>

    <x-layout.header-second>Works exists</x-layout.header-second>

    <x-layout.container>
        <x-button.link :cc="CC_DANGER" :route="route('web.basic.works-list')" label="Works detailed and specific" />
    </x-layout.container>

    <x-layout.divider/>

    <x-layout.container>
        @foreach(\App\Models\Work\Work::selectOptions() as $workOpt)
            <span class="badge text-bg-secondary">{{ $workOpt['lbl'] }}</span>
        @endforeach
    </x-layout.container>

    <x-layout.divider/>

    <x-layout.container>
        <x-pages.major-nav/>
    </x-layout.container>

</x-layout.main>
