<?php

/** @var \App\Models\Person\Person $model */

$vizaviList = [];

foreach ($model->vizavi as $vizavi) {
    $vizaviList[] = ['cc' => CC_DANGER, 'route' => route('web.person.details', ['id' => $vizavi->id]), 'label' => $vizavi->name];
}

?>@if($vizaviList)
    <x-layout.wrapper>
        <x-button.links :items="$vizaviList" />
    </x-layout.wrapper>
@endif
