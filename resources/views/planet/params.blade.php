<?php

$planet ??= new \App\Models\World\Planet();

$fName = new \App\Dto\Form\FormFieldInputDto();
$fName->id = 'name';
$fName->label = 'Planet Name';
$fName->value = $planet->name;

$fPerson = null;
$fNick = null;
if (!$planet->id) {
    $fPerson = new \App\Dto\Form\FormFieldInputDto();
    $fPerson->id = 'person';
    $fPerson->label = 'the Name of the First Person';
    $fNick = new \App\Dto\Form\FormFieldInputDto();
    $fNick->id = 'nick';
    $fNick->label = 'nick name';
}

$fWorkName = new \App\Dto\Form\FormFieldInputDto();
$fWorkName->id = 'name';
$fWorkName->label = 'Name';
$fWorkBegin = new \App\Dto\Form\FormFieldInputDto();
$fWorkBegin->id = 'begin';
$fWorkBegin->type = 'number';
$fWorkBegin->label = 'Started at';
$fWorkEnd = new \App\Dto\Form\FormFieldInputDto();
$fWorkEnd->id = 'end';
$fWorkEnd->type = 'number';
$fWorkEnd->label = 'Finished at';
$fWorkCapacity = new \App\Dto\Form\FormFieldInputDto();
$fWorkCapacity->id = 'capacity';
$fWorkCapacity->type = 'number';
$fWorkCapacity->label = 'maximum Work units';

$fEventName = new \App\Dto\Form\FormFieldInputDto();
$fEventName->id = 'name';
$fEventName->label = 'Name';
$fEventHonor = new \App\Dto\Form\FormFieldInputDto();
$fEventHonor->id = 'is_honor';
$fEventHonor->label = 'Honor';
$fEventHonor->type = 'checkbox';
$fEventRelation = new \App\Dto\Form\FormFieldInputDto();
$fEventRelation->id = 'is_relation';
$fEventRelation->label = 'Relation';
$fEventRelation->type = 'checkbox';
$fEventWork = new \App\Dto\Form\FormFieldInputDto();
$fEventWork->id = 'is_work';
$fEventWork->label = 'Work';
$fEventWork->type = 'checkbox';
$fEventSlave = new \App\Dto\Form\FormFieldInputDto();
$fEventSlave->id = 'is_slave';
$fEventSlave->label = 'Slave';
$fEventSlave->type = 'checkbox';

?><x-layout.main>
    <x-layout.header-main>Planet params</x-layout.header-main>
    <x-form.basic :route="route('web.planet.save')"
                  :btn="!$planet->id ? 'Create the Planet' : 'Rename'"
                  :fields="[$fName, $fPerson, $fNick]"></x-form.basic>

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

        <x-layout.header-second>Work</x-layout.header-second>
        <x-form.container>
            @foreach(\App\Models\World\Work::selectOptions() as $workOpt)
                <span class="badge text-bg-secondary">{{ $workOpt['lbl'] }}</span>
            @endforeach
        </x-form.container>
        <x-form.basic :route="route('web.basic.work')"
                      btn="add new Work"
                      :fields="[$fWorkName, $fWorkBegin, $fWorkEnd, $fWorkCapacity]"></x-form.basic>

        <x-layout.header-second>Events</x-layout.header-second>
        <x-form.container>
            <span class="badge text-bg-light">Exists:</span>
            @foreach(\App\Models\Person\EventType::selectOptions() as $eventOpt)
                <span class="badge text-bg-{{$eventOpt['style']}}">{{ $eventOpt['lbl'] }}</span>
            @endforeach
        </x-form.container>
        <x-form.basic :route="route('web.basic.event-type')"
                      btn="add new Event Type"
                      :fields="[$fEventName, $fEventHonor, $fEventRelation, $fEventWork, $fEventSlave]"></x-form.basic>

        <x-layout.divider></x-layout.divider>

        <x-form.container>
            <a href="{{route('web.planet.export')}}" type="button" class="btn btn-danger btn-lg">Export</a>
            <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
        </x-form.container>

    @endif
</x-layout.main>
