<?php

/** @var \App\Models\World\Life $model */

?><x-layout.header-main>
    <small><small>name:</small></small> {{ $model->person->name }} {{ $model->person->nick }}
    <br>
    <small><small>role in life:</small></small> {{ $model->role_name }}
    <br>
    <small><small>length of life:</small></small> [{{ $model->begin }}-{{ $model->end }}]Y<small><small>{{ $model->end - $model->begin }}</small></small>
    <br>
    <small><small>number of life:</small></small> {{ $model->type_name }}-{{ $model->current_type_no }}
</x-layout.header-main>
