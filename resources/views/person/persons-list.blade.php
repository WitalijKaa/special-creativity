<?php

/** @var int $year */
/** @var \App\Models\Person\Person[] $models */
/** @var int $planetLives */

$factory = new \App\Dto\Form\FormInputFactory();

$fYear = $factory->number('year', 'Year of current moment', $factory->withValue($year > 0 ? $year : null));

$titlePage = $models->count() . ' Personas';
$titlePage .= $year > 0 ? ' ' . $year . 'Y' : '';

?><x-layout.main :title="$titlePage">
    <x-layout.header-main>total population: {{ $titlePage }}</x-layout.header-main>

    @if ($planetLives)
        <x-form.basic :route="route('web.person.list')"
                      btn="show Year"
                      :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.person.list')] : null"
                      :fields="[$fYear]"></x-form.basic>

        <x-layout.divider></x-layout.divider>

        <x-layout.container>
            <x-pages.major-nav />
        </x-layout.container>

        <x-layout.divider></x-layout.divider>
    @endif

    <x-layout.container>
        @if ($planetLives)
            <x-pages.persons-list-nav />
        @endif
        <div class="list-group">
            @foreach($models as $person)
                @include('widgets.person.list-item', ['person' => $person, 'year' => $year])
            @endforeach
        </div>
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-layout.container>
        <x-pages.major-nav />
    </x-layout.container>

</x-layout.main>
