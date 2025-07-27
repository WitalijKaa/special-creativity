<?php

/** @var \App\Models\World\Life $model */

?><a href="{{route('web.person.details', ['id' => $model->person->id])}}" type="button" class="btn btn-success btn-lg">{{$model->person->name}}</a>
<a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
<a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
