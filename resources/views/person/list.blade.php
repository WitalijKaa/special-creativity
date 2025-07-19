<?php

/** @var \App\Models\Person\Person[] $models */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year ?? 0;

$lifeBacked = [1 => 'primary', 2 => 'success', 3 => 'danger', 4 => 'warning'];
$vPerson = new \App\Models\View\PersonView();

?>
<x-layout.main>
    <x-layout.header-main>Personas</x-layout.header-main>

    <x-form.basic :route="route('web.planet.params')"
                  btn="show Year"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    <x-layout.container>
        <div class="list-group">
            @foreach($models as $person)
                @php($backClass = !$person->last_life ? 'secondary' : ($lifeBacked[$person->last_life->type_id] ?? 'light'))
                <a href="{{route('web.person.details', ['id' => $person->id])}}"
                   class="list-group-item list-group-item-action list-group-item-{{$backClass}}">
                    <div class="d-flex w-100 justify-content-between">
                        <h4 class="mb-1">{{$person->name}} {!!$vPerson->labelAuthor($person)!!} {!!$vPerson->labelCreations($person)!!}</h4>
                        <small>
                            {!!$vPerson->labelLivesTotalSimple($person)!!}
                            @if($person->force_person == \App\Models\Person\Person::FORCE)
                                <span class="badge text-bg-success">Can create Life</span>
                            @endif
                            @if($person->may_be_girl_easy)
                                <span class="badge text-bg-warning">May be a Girl</span>
                            @endif
                        </small>
                    </div>
                    <p class="mb-1">
                        <strong>{!!$vPerson->labelLives($person)!!}</strong>
                        <small>{!!$vPerson->labelLastYearOfExistence($person)!!}</small>
                    </p>
                    <small>{!!$vPerson->labelForce($person)!!}</small>
                </a>
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-outline-primary btn-lg">Basic</a>
        <a href="{{route('web.person.list', ['sort' => 'desc_last_year'])}}" type="button" class="btn btn-outline-primary btn-lg">Last Year</a>
    </x-form.container>

</x-layout.main>
