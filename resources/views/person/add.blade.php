<?php

$fName = new \App\Dto\Form\FormFieldInputDto();
$fName->id = 'name';
$fName->label = 'Full Name';

?><x-layout.main>
    <x-layout.header-main>new Persona</x-layout.header-main>
    <x-form.basic :route="route('web.planet.params')"
                  btn="Create new Human Persona"
                  :fields="[$fName]"></x-form.basic>
</x-layout.main>
