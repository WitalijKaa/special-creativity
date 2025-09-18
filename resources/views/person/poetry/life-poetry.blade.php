<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */

$factory = new \App\Dto\Form\FormInputFactory();

$personAddParagraph = [
    $factory->textarea('paragraph'),
    $factory->select('lang', \App\Models\Poetry\Poetry::selectOptions(), 'Which language?'),
    $factory->number('begin', 'start of paragraph'),
    $factory->number('end', 'end of paragraph'),
];

?><x-layout.main :title="$life->person->name . ' ' . $life->type_name . '-' . $life->current_type_no">
    <x-layout.header-main>
        {{ $life->person->name }} {{ $life->person->nick }} {{ $life->role_name }}
        <br>
        [{{ $life->begin }}-{{ $life->end }}]Y<small><small>{{ $life->end - $life->begin }}</small></small>
        <br>
        {{ $life->type_name }}-{{ $life->current_type_no }}
    </x-layout.header-main>

    <x-layout.header-second>poetry of Life...</x-layout.header-second>

    <x-form.basic :route="route('web.person.paragraph-add', ['life_id' => $life->id])"
                  btn="add Paragraph"
                  :fields="$personAddParagraph"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-layout.container>
        @foreach($poetry as $paragraph)
            <p>{{$paragraph->text}}</p>
        @endforeach
    </x-layout.container>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-form.container>

    <x-layout.header-second>Work 💪🏻 is {{ $life->lifeWork->workYears }}</x-layout.header-second>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'person' => $life->person, 'lifeWork' => $life->lifeWork])
    </x-layout.container>

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-form.container>

</x-layout.main>
