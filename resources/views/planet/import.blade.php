<?php

$factory = new \App\Dto\Form\FormInputFactory();

$importForm = [
    $factory->input('directory'),
];

?>
<x-layout.main title="Import">
    <x-layout.header-main>Planet import</x-layout.header-main>

    <x-layout.container>
        <x-form.basic :route="route('web.planet.import')"
                      btn="Import"
                      :fields="$importForm"></x-form.basic>
    </x-layout.container>

    <x-session.app-msg />

    <x-layout.divider />

    <x-layout.container>
        <x-pages.major-nav />

        <x-layout.wrapper>
            <x-button.links :items="[
                ['cc' => CC_DANGER, 'route' => route('web.planet.export'), 'label' => 'Export'],
                ['cc' => CC_DANGER, 'route' => route('web.planet.import'), 'label' => 'Import'],
            ]" />
        </x-layout.wrapper>
    </x-layout.container>

</x-layout.main>
