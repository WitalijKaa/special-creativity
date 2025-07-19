<?php

/** @var \App\Models\World\Planet $planet */
/** @var \Illuminate\Support\Collection|\App\Models\Person\Person[] $persons */
/** @var \Illuminate\Support\Collection|\App\Models\World\Life[] $lives */

$json = [
    'planet' => $planet->archive(),
    'persons' => $persons->map(fn (\App\Models\Person\Person $model) => $model->archive()),
    'lives' => $lives->map(fn (\App\Models\World\Life $model) => $model->archive()),
];

?><x-layout.main>
    <x-layout.header-main>Planet export</x-layout.header-main>

    <x-layout.container>
        <pre>{!!json_encode($json)!!}</pre>
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-layout.container>
        <pre>{!!json_encode($json, JSON_PRETTY_PRINT)!!}</pre>
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
    </x-form.container>
</x-layout.main>
