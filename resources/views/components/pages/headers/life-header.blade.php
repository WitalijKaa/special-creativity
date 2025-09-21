<?php

/** @var \App\Models\World\Life $model */

?><x-layout.header-main>
    {{ $model->person->name }} {{ $model->person->nick }} {{ $model->role_name }}
    <br>
    [{{ $model->begin }}-{{ $model->end }}]Y<small><small>{{ $model->end - $model->begin }}</small></small>
    <br>
    {{ $model->type_name }}-{{ $model->current_type_no }}
</x-layout.header-main>
