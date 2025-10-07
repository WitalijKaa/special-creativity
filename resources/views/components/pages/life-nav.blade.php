<?php

/** @var \App\Models\World\Life $model */

$veryPrevious ??= false;
$next = $model->next_vs_type;

$personsBefore = [];
if ($prev = $model->prev_vs_type) {
    $personsBefore[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.details-life', ['person_id' => $prev->person_id, 'life_id' => $prev->id]), 'label' => 'prev ' . $prev->type_name . '-' . $prev->current_type_no . ' ' . $prev->role_name];
}
if ($veryPrevious && ($prev = $model->prev_life)) {
    $personsBefore[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.details-life', ['person_id' => $prev->person_id, 'life_id' => $prev->id]), 'label' => 'prev-life ' . $prev->type_name . '-' . $prev->current_type_no . ' ' . $prev->role_name];
}
if ($veryPrevious) {
    $personsBefore[] = ['cc' => CC_WARNING, 'route' => route('web.person.details-life', ['person_id' => $model->person->id, 'life_id' => $model->id]), 'label' => 'life ' . $model->type_name . '-' . $model->current_type_no . ' ' . $model->role_name];
}
$personsBefore[] = ['cc' => CC_DANGER, 'route' => route('web.person.details', ['id' => $model->person->id]), 'label' => $model->person->name];
if ($veryPrevious && ($next = $model->next_life)) {
    $personsBefore[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.details-life', ['person_id' => $next->person_id, 'life_id' => $next->id]), 'label' => 'next-life ' . $next->type_name . '-' . $next->current_type_no . ' ' . $next->role_name];
}
if ($next = $model->next_vs_type) {
    $personsBefore[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.details-life', ['person_id' => $next->person_id, 'life_id' => $next->id]), 'label' => 'next ' . $next->type_name . '-' . $next->current_type_no . ' ' . $next->role_name];
}

?><x-pages.major-nav :forced-before-person="$personsBefore" />
<x-pages.person-details-nav :model="$model->person" />
@if(!Route::is('web.person.poetry-life'))
    <x-layout.wrapper>
        <x-button.links :items="[
                    ['cc' => CC_SUCCESS, 'route' => route('web.person.poetry-life', ['life_id' => $model->id]), 'label' => 'Poetry'],
                    ['dropdown' => ['label' => 'Automations', 'cc' => CC_DARK, 'items' => [
                        ['route' => route('web.routine.life-work-army', ['id' => $model->id]), 'label' => 'make WorkArmyLife'],
                    ]]]
                ]" />
    </x-layout.wrapper>
@endif
