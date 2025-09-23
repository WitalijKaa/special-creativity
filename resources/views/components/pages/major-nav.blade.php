@if(!Route::is('web.person.list'))
    <x-layout.wrapper>
        <x-button.link :cc="CC_INFO" :route="route('web.person.list')" label="Personas" :badge="$persons ?? null" />
    </x-layout.wrapper>
@endif

<x-layout.wrapper>
    <x-button.links :items="[
                ['route' => route('web.basic.events'), 'label' => 'Events'],
                ['route' => route('web.planet.works-list'), 'label' => 'Work'],
                ['cc' => CC_SUCCESS, 'route' => route('web.person.poetry-words'), 'label' => 'Poetry words'],
                ['cc' => CC_LIGHT, 'route' => route('web.prediction.future'), 'label' => 'Predictions'],
                ['cc' => CC_DARK, 'route' => route('web.visual.lives-timeline'), 'label' => 'Lives Timeline'],
                ['cc' => CC_DARK, 'route' => route('web.visual.years-population'), 'label' => 'Population vs Year'],
            ]" />
</x-layout.wrapper>

@if(!Route::is('web.space.basic'))
    <x-layout.wrapper>
        <x-button.link :cc="CC_SECONDARY" :route="route('web.space.basic')" label="Planet" />
    </x-layout.wrapper>
@endif
