<?php

$factory = new \App\Dto\Form\FormInputFactory();

$planet ??= new \App\Models\World\Planet();

$planetSave = [
    $factory->input('name', 'Planet Name', $factory->withValue($planet->name)),
];
if (!$planet->id) {
    $planetSave[] = $factory->input('person', 'the Name of the First Person');
    $planetSave[] = $factory->input('nick', 'nick name');
}

$basicEventType = [
    $factory->input('name'),
    $factory->checkbox('is_honor', 'Honor'),
    $factory->checkbox('is_relation', 'Relation'),
    $factory->checkbox('is_work', 'Work'),
    $factory->checkbox('is_slave', 'Slave'),
];

?><x-layout.main>
    <x-layout.header-main>Planet params</x-layout.header-main>
    <x-form.basic :route="route('web.planet.save')"
                  :btn="!$planet->id ? 'Create the Planet' : 'Rename'"
                  :fields="$planetSave"></x-form.basic>

    @if($planet->id)

        <x-layout.header-second>Life types</x-layout.header-second>
        <x-form.container>
            <span class="badge text-bg-light">Exists:</span>
            @foreach(\App\Models\World\Life::NAME as $lifeTypeName)
                <span class="badge text-bg-success">{{ $lifeTypeName }}</span>
            @endforeach
        </x-form.container>

        <x-layout.header-second>Life roles</x-layout.header-second>
        <x-form.container>
            <span class="badge text-bg-light">Exists:</span>
            @foreach(\App\Models\World\Life::ROLE as $roleName)
                <span class="badge text-bg-primary">{{ $roleName }}</span>
            @endforeach
        </x-form.container>

        @include('widgets.planet.works-simple')

        <x-layout.header-second>Events</x-layout.header-second>
        <x-form.container>
            <span class="badge text-bg-light">Exists:</span>
            @foreach(\App\Models\Person\EventType::selectOptions() as $eventOpt)
                <span class="badge text-bg-{{$eventOpt['style']}}">{{ $eventOpt['lbl'] }}</span>
            @endforeach
        </x-form.container>
        <x-form.basic :route="route('web.basic.event-type')"
                      btn="add new Event Type"
                      :fields="$basicEventType"></x-form.basic>

        <x-layout.divider></x-layout.divider>

        <x-form.container>
            <a href="{{route('web.planet.export')}}" type="button" class="btn btn-danger btn-lg">Export</a>
            @include('components.pages.major-nav')
            <br><br>
            <a href="{{route('web.person.poetry-words')}}" type="button" class="btn btn-danger btn-lg">Poetry words</a>
            <a href="{{route('web.prediction.future')}}" type="button" class="btn btn-warning btn-lg">Predictions</a>
            <br><br>
            <a href="{{route('web.visual.lives-timeline')}}" type="button" class="btn btn-info btn-lg">Lives-T</a>
            <a href="{{route('web.visual.years-population')}}" type="button" class="btn btn-info btn-lg">Years-Persons</a>
            <br><br>
            <a href="{{route('web.routine.create-persons')}}" type="button" class="btn btn-dark btn-lg">create S-LIFE</a>
            <a href="{{route('web.routine.allods-live-cycle')}}" type="button" class="btn btn-dark btn-lg">Cycle Allods</a>
            <a href="{{route('web.routine.planet-live-cycle')}}" type="button" class="btn btn-dark btn-lg">Cycle Planet</a>
        </x-form.container>

    @endif
</x-layout.main>
