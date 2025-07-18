<?php

$planet ??= new \App\Models\World\Planet();

$fName = new \App\Dto\Form\FormFieldInputDto();
$fName->id = 'name';
$fName->label = 'PlanetName';
$fName->value = $planet->name;

?><x-layout.main>
    <header class="container text-center">
        <h1>Planet params</h1>
        <hr class="border border-danger border-2">
    </header>

    <x-form.basic :route="route('web.planet.save')"
                  :btn="!$planet->id ? 'Create the Planet' : 'Change Everything'"
                  :fields="[$fName]"></x-form.basic>
</x-layout.main>
