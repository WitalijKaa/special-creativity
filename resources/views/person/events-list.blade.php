<?php

/** @var int $year */
/** @var \App\Models\Collection\PersonEventCollection|\App\Models\Person\PersonEvent[] $models */

$factory = new \App\Dto\Form\FormInputFactory();

$fYear = $factory->number('year', 'Year of current moment', $factory->withValue($year > 0 ? $year : null));


?>
<x-layout.main title="Events">
    <x-layout.header-main>Events</x-layout.header-main>

    <x-form.basic :route="route('web.basic.events')"
                  btn="show Year"
                  :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.basic.events')] : null"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $models, 'showWorks' => false, 'showGender' => true])
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.major-nav')
    </x-form.container>

</x-layout.main>
