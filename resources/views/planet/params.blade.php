<?php

/** @var \App\Models\World\LifeType[] $lifeTypes */

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

$fLifeType = new \App\Dto\Form\FormFieldInputDto();
$fLifeType->id = 'life';
$fLifeType->label = 'LifeType';

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
            @foreach($lifeTypes as $item)
                <span class="badge {{ $item->system ? 'text-bg-success' : 'text-bg-primary' }}">{{ $item->name }}</span>
            @endforeach
        </x-form.container>

        <x-form.basic :route="route('web.basic.life-type')"
                      btn="add a new Life Type"
                      :fields="[$fLifeType]"></x-form.basic>

        <x-layout.divider></x-layout.divider>

        <x-form.container>
            <a href="{{route('web.planet.export')}}" type="button" class="btn btn-danger btn-lg">Export</a>
            <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
        </x-form.container>

    @endif
</x-layout.main>
