<?php

/** @var \App\Models\World\Life $model */

$prev = $model->prev_vs_type;
$next = $model->next_vs_type;

?>@if($prev)
    <a href="{{route('web.person.details-life', ['person_id' => $prev->person_id, 'life_id' => $prev->id])}}" type="button" class="btn btn-warning btn-lg">{{ $prev->type_name }}-{{ $prev->current_type_no }} {{ $prev->role_name }}</a>
@endif
<a href="{{route('web.person.details', ['id' => $model->person->id])}}" type="button" class="btn btn-success btn-lg">{{$model->person->name}}</a>
@if($next)
    <a href="{{route('web.person.details-life', ['person_id' => $next->person_id, 'life_id' => $next->id])}}" type="button" class="btn btn-warning btn-lg">{{ $next->type_name }}-{{ $next->current_type_no }} {{ $next->role_name }}</a>
@endif
<br><br>
<a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
<a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
<a href="{{route('web.planet.works-list')}}" type="button" class="btn btn-primary btn-lg">Work</a>
@if($model->person->only_vizavi)
    <a href="{{route('web.person.details', ['id' => $model->person->only_vizavi->id])}}" type="button" class="btn btn-danger btn-lg">{{ $model->person->only_vizavi->name }}</a>
@endif
