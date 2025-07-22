<?php

$planet ??= new \App\Models\World\Planet();

$fName = new \App\Dto\Form\FormFieldInputDto();
$fName->id = 'name';
$fName->label = 'Planet Name';
$fName->value = $planet->name;

$fPerson = null;
if (!$planet->id) {
    $fPerson = new \App\Dto\Form\FormFieldInputDto();
    $fPerson->id = 'person';
    $fPerson->label = 'the Name of the First Person';
}

?><x-layout.main>
    <x-layout.header-main>Planet params</x-layout.header-main>
    <x-form.basic :route="route('web.planet.save')"
                  :btn="!$planet->id ? 'Create the Planet' : 'Rename'"
                  :fields="[$fName, $fPerson]"></x-form.basic>


    @if($planet->id)

        <x-layout.header-second>Life types</x-layout.header-second>

        <x-form.container>
            <h6>Exists</h6>
            <span class="badge text-bg-secondary">Exists:</span>
            @foreach(\App\Models\World\Life::NAME as $lifeTypeName)
                <span class="badge {{ 'text-bg-success' }}">{{ $lifeTypeName }}</span>
            @endforeach
        </x-form.container>

        <x-layout.divider></x-layout.divider>

        <x-form.container>
            <a href="{{route('web.planet.export')}}" type="button" class="btn btn-danger btn-lg">Export</a>
            <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
        </x-form.container>

    @endif
</x-layout.main>
