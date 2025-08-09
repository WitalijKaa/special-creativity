<?php

/** @var int $year */
/** @var \App\Models\Person\Person[] $models */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year > 0 ? $year : null;

$titlePage = $models->count() . ' Personas';
$titlePage .= $year > 0 ? ' ' . $year . 'Y' : '';

?><x-layout.main :title="$titlePage">
    <x-layout.header-main>{{ $titlePage }}</x-layout.header-main>

    <x-form.basic :route="route('web.person.list')"
                  btn="show Year"
                  :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.person.list')] : null"
                  :fields="[$fYear]"></x-form.basic>

    <x-form.container>
        @include('components.pages.persons-list-nav')
    </x-form.container>

    <x-layout.divider></x-layout.divider>

    <x-layout.container>
        <div class="list-group">
            @foreach($models as $person)
                @include('widgets.person.list-item', ['person' => $person, 'year' => $year])
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.persons-list-nav')
    </x-form.container>

</x-layout.main>
