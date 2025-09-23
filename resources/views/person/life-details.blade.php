<?php

/** @var \App\Models\World\Life $model */
/** @var \Illuminate\Support\Collection|\App\Models\World\Life[] $connections */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Support\Collection|\App\Models\Work\Work[] $work */

$factory = new \App\Dto\Form\FormInputFactory();

$personAddEvent = [
    $factory->number('begin', 'start of Event', $factory->withValue(old('begin') ?? ($model->begin + 10))),
    $factory->number('end', 'finish of Event', $factory->withValue(old('end') ?? ($model->begin + 50))),
    $factory->select('type', \App\Models\Person\EventType::selectOptions(), 'What was It?'),
    $factory->select('work', \App\Models\Work\Work::selectSpecificOptions($work), 'Worked at'),
    $factory->number('strong', 'how strong % worked?'),
    $factory->textarea('comment', 'more Description of Event'),
];
for ($ix = 1; $ix <= 13; $ix++) {
    $personAddEvent[] = $factory->select('connect_' . $ix, \App\Models\World\Life::selectConnectionOptions($connections), 'With who?');
    if ($ix >= $connections->count()) {
        break;
    }
}

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($model)">
    <x-pages.headers.life-header :model="$model"></x-pages.headers.life-header>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'person' => $model->person, 'lifeWork' => $model->lifeWork])
    </x-layout.container>

    <x-layout.header-second>Work 💪🏻 is {{ $model->lifeWork->workYears }}</x-layout.header-second>

    <x-layout.container>
        @include('components.pages.life-nav', ['model' => $model])
    </x-layout.container>

    <x-layout.header-second>edit Events of this Life</x-layout.header-second>

    <x-form.basic :route="route('web.person.add-event', ['id' => $model->id])"
                  btn="add Event"
                  :fields="$personAddEvent"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-layout.container>
        @include('components.pages.life-nav', ['model' => $model])
    </x-layout.container>

</x-layout.main>
