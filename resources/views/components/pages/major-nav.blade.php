@if(!Route::is('web.person.list'))
    @if(empty($forcedBeforePerson))
        <x-layout.wrapper>
            <x-button.link :cc="CC_INFO" :route="route('web.person.list')" label="Personas" :badge="$persons ?? null" />
        </x-layout.wrapper>
    @else
        <x-layout.wrapper>
            <x-button.links :items="array_merge($forcedBeforePerson, [['cc' => CC_INFO, 'route' => route('web.person.list'), 'label' => 'Personas']])" />
        </x-layout.wrapper>
    @endif
@endif

<x-layout.wrapper>
    <x-button.links :items="[
                ['route' => route('web.basic.events'), 'label' => 'Events'],
                (!Route::is('web.basic.work-create') ? ['route' => route('web.basic.work-create'), 'label' => 'Works'] : ['route' => route('web.basic.works-list'), 'label' => 'Works detailed']),
                ['cc' => CC_SUCCESS, 'route' => route('web.person.poetry-words'), 'label' => 'Poetry words'],
                ['cc' => CC_LIGHT, 'route' => route('web.prediction.future'), 'label' => 'Predictions'],
                ['cc' => CC_DARK, 'route' => route('web.visual.lives-timeline'), 'label' => 'Lives Timeline'],
                ['cc' => CC_DARK, 'route' => route('web.visual.years-population'), 'label' => 'Population vs Year'],
            ]" />
</x-layout.wrapper>

@if(!Route::is('web.basic.space'))
    <x-layout.wrapper>
        <x-button.link :cc="CC_SECONDARY" :route="route('web.basic.space')" label="Planet" />
    </x-layout.wrapper>
@endif
