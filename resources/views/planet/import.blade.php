<?php

$factory = new \App\Dto\Form\FormInputFactory();

$importForm = [
    $factory->input('directory'),
];

?>
<x-layout.main title="Import">
    <x-layout.header-main>Planet import</x-layout.header-main>

    <x-session.success></x-session.success>

    <x-layout.container>
        <x-form.basic :route="route('web.planet.import')"
                      btn="Import"
                      :fields="$importForm"></x-form.basic>
    </x-layout.container>

    <x-layout.divider />

    <x-layout.container>
        <a href="{{ route('web.basic.space') }}" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{ route('web.person.list') }}" class="btn btn-primary btn-lg">Personas</a>
    </x-layout.container>

</x-layout.main>
