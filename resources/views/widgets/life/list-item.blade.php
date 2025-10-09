<?php

/** @var \App\Models\World\Life $model */

$planet = \App\Models\World\Planet::correctPlanet();

?><a href="{{route('web.person.details-life', ['person_id' => $model->person_id, 'life_id' => $model->id])}}" class="list-group-item list-group-item-action list-group-item-{{$vPerson->lifeBack($model)}}">

    <div class="d-flex w-100 justify-content-between mb-1">

        <div class="d-flex w-50 justify-content-between">
            <h3>
                {{$model->type_name . '-' . $model->current_type_no}}
                {!!$vPerson->labelLifeIsDeepLove($model)!!}
                {!!$vPerson->labelLifeIsHoly($model)!!}
                {!!$vPerson->labelLifeIsSlave($model)!!}
            </h3>
            <h3>Years {{$model->begin}}-{{$model->end}}</h3>
        </div>

        <div class="d-flex w-50 justify-content-between">
            <div></div>
            <p>
                @if($model->cachedPoetryCount)
                    📖 {{$model->cachedPoetryCount}} 📖&nbsp;&nbsp;
                @endif
                @if($model->begin_force_person == \App\Models\Person\Person::FORCE)
                    <span class="badge text-bg-success">Can create Life</span>
                @endif
                @if($model->mayBeGirlEasy($planet))
                    <span class="badge text-bg-warning">May be a Girl</span>
                @endif
                <span class="badge text-bg-secondary">{{$model->end - $model->begin}} years</span>
            </p>
        </div>
    </div>

    <div class="d-flex w-100 justify-content-between">

        <h5><strong>{{$model->role_name}} {!! $vPerson->lifeGender($model) !!}</strong></h5>

        <p>{!! $vPerson->labelForce($model) !!}</p>

    </div>
</a>
