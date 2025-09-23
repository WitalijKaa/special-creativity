<?php

$nav = [];
if (request()->get('sort')) {
    $nav[] = ['route' => route('web.person.list'), 'label' => 'Basic'];
}
if (request()->get('sort') != 'last_year') {
    $nav[] = ['route' => route('web.person.list', ['sort' => 'last_year']), 'label' => 'Soon time'];
}
if (request()->get('sort') != 'desc_last_year') {
    $nav[] = ['route' => route('web.person.list', ['sort' => 'desc_last_year']), 'label' => 'Far time'];
}
?><x-layout.wrapper>
    <x-button.links :items="$nav" />
</x-layout.wrapper>
