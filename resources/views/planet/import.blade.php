<?php

$factory = new \App\Dto\Form\FormInputFactory();

$importForm = [
    $factory->input('directory'),
];

?>
<x-layout.main title="Import">
    <x-layout.header-main>Planet import</x-layout.header-main>

    <x-session.success></x-session.success>

    <x-form.container>
        <x-form.basic :route="route('web.planet.import')"
                      btn="Import"
                      :fields="$importForm"></x-form.basic>
    </x-form.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{ route('web.planet.params') }}" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{ route('web.person.list') }}" class="btn btn-primary btn-lg">Personas</a>
    </x-form.container>

</x-layout.main>
