<?php

/** @var int $year */
/** @var \App\Models\Collection\PersonEventCollection|\App\Models\Person\PersonEvent[] $models */

$factory = new \App\Dto\Form\FormInputFactory();

$fYear = $factory->number('year', 'Year of current moment', $factory->withValue($year > 0 ? $year : null));

$basicEventType = [
    $factory->input('name'),
    $factory->checkbox('is_honor', 'Honor'),
    $factory->checkbox('is_relation', 'Relation'),
    $factory->checkbox('is_work', 'Work'),
    $factory->checkbox('is_slave', 'Slave'),
];

?>
<x-layout.main title="Events">
    <x-layout.header-main>Events</x-layout.header-main>

    <x-layout.container-sm>
        <span class="badge text-bg-light">Exists:</span>
        @foreach(\App\Models\Person\EventType::selectOptions() as $eventOpt)
            <span class="badge text-bg-{{$eventOpt['style']}}">{{ $eventOpt['lbl'] }}</span>
        @endforeach
    </x-layout.container-sm>
    <x-form.basic :route="route('web.basic.event-type')"
                  btn="add new Event Type"
                  :fields="$basicEventType"></x-form.basic>

    <x-layout.divider />

    <x-layout.container>
        <x-pages.major-nav />
    </x-layout.container>

    <x-layout.divider />

    <x-form.basic :route="route('web.basic.events')"
                  btn="show Year"
                  :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.basic.events')] : null"
                  :fields="[$fYear]"></x-form.basic>

    <x-layout.divider />

    <x-layout.container>
        @include('widgets.person.events', ['events' => $models, 'showWorks' => false, 'showGender' => true])
    </x-layout.container>

    <x-layout.divider />

    <x-layout.container>
        <x-pages.major-nav />
    </x-layout.container>

</x-layout.main>
