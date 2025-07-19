<?php

/** @var \App\Models\Person\Person[] $models */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year ?? 0;

$vPerson = new \App\Models\Person\PersonView();

?><x-layout.main>
    <x-layout.header-main>Personas</x-layout.header-main>

    <x-form.basic :route="route('web.planet.params')"
                  btn="show Year"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    <x-layout.container>
        <div class="list-group">
            @foreach($models as $person)
                <a href="{{route('web.person.details', ['id' => $person->id])}}" class="list-group-item list-group-item-action list-group-item-primary">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{$person->name}}</h5>
                        <small>
                            {{$vPerson->labelLivesTotalSimple($person)}}
                            @if($person->force_person == 100)<span class="badge text-bg-success">Can create Life</span>@endif
                            @if($person->force_woman == 100)<span class="badge text-bg-warning">May be a Girl</span>@endif
                        </small>
                    </div>
                    <p class="mb-1">{{$vPerson->labelLives($person)}}</p>
                    <small>{{$vPerson->labelForce($person)}}</small>
                </a>
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
{{--        <a href="{{route('web.person.form')}}" type="button" class="btn btn-success btn-lg">new Persona</a>--}}
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
    </x-form.container>

</x-layout.main>
