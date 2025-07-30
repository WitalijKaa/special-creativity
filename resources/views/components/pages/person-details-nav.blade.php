<?php

/** @var \App\Models\Person\Person $model */

?><a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
<a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
<a href="{{route('web.planet.works-list')}}" type="button" class="btn btn-primary btn-lg">Work</a>
@if($model->only_vizavi)
    <a href="{{route('web.person.details', ['id' => $model->only_vizavi->id])}}" type="button" class="btn btn-danger btn-lg">{{ $model->only_vizavi->name }}</a>
@endif
