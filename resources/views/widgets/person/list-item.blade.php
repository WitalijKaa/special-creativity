<?php

$showInfo ??= null;

/** @var int $year */
/** @var \App\Models\Person\Person $person */
$viewLife = $year > 0 ?
    ($person->planetLife($year) ? $person->planetLife($year) : false)
    : null;

$vPerson = new \App\Models\View\PersonView();

?><a href="{{route('web.person.details', ['id' => $person->id])}}" class="list-group-item list-group-item-action list-group-item-{{$vPerson->lifeBack($viewLife ?: $person->last_life)}}">
    <div class="d-flex w-100 justify-content-between mb-1">

        <div class="d-flex w-50 justify-content-between">
            <h3>
                {{ $person->name }}
                <small><small>
                        {!! ($viewLife ? ($vPerson->labelAge($viewLife, $year) . $vPerson->lifeGenre($viewLife)) : '') !!}
                </small></small>
            </h3>
            <h5><strong><em>{{$person->nick}}</em></strong> {!! $vPerson->space2() !!}</h5>
        </div>

        <div class="d-flex w-50 justify-content-between">
            <h3>{!!$vPerson->labelLives($person, $year)!!} {!!$vPerson->labelCreations($person, $year)!!}</h3>
            <p>
                <em>{!!$vPerson->labelLivesTotalSimple($person, $year)!!}</em>
                @if(($viewLife ? $viewLife->begin_force_person : $person->force_person) == \App\Models\Person\Person::FORCE)
                    <span class="badge text-bg-success">Can create Life</span>
                @endif
                @if($person->mayBeGirlEasy($year))
                    <span class="badge text-bg-warning">May be a Girl</span>
                @endif
            </p>
        </div>
    </div>

    <div class="d-flex w-100 justify-content-between">

        <div class="d-flex w-50 justify-content-between">
            <h3>
                {{$showInfo}}
                <small><small><small>{!!$vPerson->labelAuthor($person)!!}</small></small></small>
            </h3>
            <h5>{!!$vPerson->labelSlaveLife($person, $year)!!} {!!$vPerson->labelHolyLife($person, $year)!!} {!!$vPerson->labelVizavi($person)!!} {!! $vPerson->space2() !!}</h5>
        </div>

        <div class="d-flex w-50 justify-content-between">
            <h5>
                @if ($viewLife)
                    <strong>{{$viewLife->type_name . '-' . $viewLife->current_type_no}}</strong>
                    {!!$vPerson->labelLifeIsDeepLove($viewLife)!!}
                    {!!$vPerson->labelLifeIsHoly($viewLife)!!}
                    {!!$vPerson->labelLifeIsSlave($viewLife)!!}
                @elseif (false === $viewLife)
                    on Allods
                @else
                    &nbsp;
                @endif
            </h5>
            @if(!$showInfo)
                <h6>
                    {!! !($year > 0) ? $vPerson->labelLastYearOfExistence($person) : '' !!}
                    {!!$vPerson->labelForce($person)!!}
                </h6>
            @else
                <h3>{{$showInfo}}</h3>
            @endif
        </div>
    </div>
</a>
