<?php

/** @var \App\Models\World\Planet $planet */
/** @var int $persons */

?><x-layout.main :title="$planet->name . ' planet'">
    <x-layout.header-main>space around {{$planet->name}} planet</x-layout.header-main>

    <x-layout.container-sm>
        <span class="badge text-bg-light">Life types:</span>
        @foreach(\App\Models\World\Life::NAME as $lifeTypeName)
            <span class="badge text-bg-success">{{ $lifeTypeName }}</span>
        @endforeach
    </x-layout.container-sm>

    <x-layout.container-sm>
        <span class="badge text-bg-light">Life roles:</span>
        @foreach(\App\Models\World\Life::ROLE as $roleName)
            <span class="badge text-bg-primary">{{ $roleName }}</span>
        @endforeach
    </x-layout.container-sm>

    <x-layout.container-sm>
        <span class="badge text-bg-light">Events:</span>
        @foreach(\App\Models\Person\EventType::selectOptions() as $eventOpt)
            <span class="badge text-bg-{{$eventOpt['style']}}">{{ $eventOpt['lbl'] }}</span>
        @endforeach
    </x-layout.container-sm>

    <x-layout.divider></x-layout.divider>

    <x-layout.container-md>

        <x-pages.major-nav :persons="$persons" />

        <x-layout.wrapper>
            <x-button.links :items="[
                ['cc' => CC_DANGER, 'route' => route('web.planet.export'), 'label' => 'Export'],
                ['cc' => CC_DANGER, 'route' => route('web.planet.import'), 'label' => 'Import'],
                ['dropdown' => ['label' => 'Automations', 'cc' => CC_DARK, 'items' => [
                    ['route' => route('web.routine.create-persons'), 'label' => 'in Allod-Creation do Creation'],
                    ['route' => route('web.routine.allods-live-cycle'), 'label' => 'Live at Allods'],
                    ['route' => route('web.routine.planet-live-cycle'), 'label' => 'Live at Planet'],
                ]]]
            ]" />
        </x-layout.wrapper>

        <x-layout.wrapper>
            <x-button.link-wide :cc="CC_DANGER" :route="route('web.logout')" label="Exit {{Auth::user()->login}}" />
        </x-layout.wrapper>

        <div style="height: 5vh;"></div>
        <x-layout.wrapper>
            <x-button.link :cc="CC_SECONDARY" :route="route('web.planet.params')" label="old-params" />
        </x-layout.wrapper>

    </x-layout.container-md>
</x-layout.main>
