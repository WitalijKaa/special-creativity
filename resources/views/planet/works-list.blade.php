<?php

/** @var int $year */
/** @var \App\Models\Work\Work[] $models */

$fYear = new \App\Dto\Form\FormFieldInputDto();
$fYear->id = 'year';
$fYear->label = 'Year of current moment';
$fYear->type = 'number';
$fYear->value = $year > 0 ? $year : null;

?>
<x-layout.main title="Work">
    <x-layout.header-main>Work</x-layout.header-main>

    <x-form.basic :route="route('web.planet.works-list')"
                  btn="show Year"
                  :btn-warn="$year > 0 ? ['lbl' => 'Back', 'href' => route('web.planet.works-list')] : null"
                  :fields="[$fYear]"></x-form.basic>

    <div class="mb-5 mt-5"></div>

    <x-layout.container>
        <div class="list-group">
            @foreach($models as $work)
                @php($isCorrected = $work->calculations->begin == $work->begin && $work->calculations->end == $work->end)

                <a href="{{route('web.planet.works-details', ['id' => $work->id])}}" class="list-group-item list-group-item-action list-group-item-primary">

                    <div class="d-flex w-100 justify-content-between mb-1">
                        <div class="d-flex w-50 justify-content-between">
                            <h4>{{ $work->name }}</h4>
                            <h4>
                                {{ $work->calculations->workers->count() }}
                                @if($work->events->count() > 1)<sup>{{ $work->events->count() }}</sup>@endif
                                🧑🏻
                                &nbsp;&nbsp;&nbsp;&nbsp;
                            </h4>
                        </div>

                        <div class="d-flex w-50 justify-content-between">
                            <h4>
                                💪🏻 {{ $work->calculations->workYears }}
                                @if($work->consumers)
                                    🛍️ {{ (int)$work->consuming_days_per_year }}
                                    <small><small>👨🏻‍🍳 {{ $work->consumers }}</small></small>
                                @endif
                            </h4>
                            <h4>
                                @if(!$isCorrected)<em>@endif
                                [{{ $work->calculations->begin }}-{{ $work->calculations->end }}]Y
                                @if(!$isCorrected)</em>@endif
                            </h4>
                        </div>
                    </div>

                </a>
            @endforeach
        </div>
    </x-layout.container>

    @include('widgets.planet.works-simple')

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
    </x-form.container>

</x-layout.main>
