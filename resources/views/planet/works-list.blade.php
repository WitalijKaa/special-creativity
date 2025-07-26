<?php

/** @var \App\Models\World\Work[] $models */

?><x-layout.main title="Work">
    <x-layout.header-main>Work</x-layout.header-main>

    <x-layout.container>
        <div class="list-group">
            @foreach($models as $work)

                <a href="{{route('web.person.details', ['id' => $work->id])}}" class="list-group-item list-group-item-action list-group-item-primary">
                    <div class="d-flex w-100 justify-content-between mb-1">

                        <div class="d-flex w-50 justify-content-between">
                            <h4>{{ $work->name }}</h4>
                            <h4>{{ $work->calculations->workers->count() }} 🧑🏻 &nbsp;&nbsp;&nbsp;&nbsp;</h4>
                        </div>

                        <div class="d-flex w-50 justify-content-between">
                            <h4>💪🏻 {{ $work->calculations->workYears }}</h4>
                            <h4>[{!! $work->calculations->begin !!}-{!! $work->calculations->end !!}]Y</h4>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </x-layout.container>

    @include('widgets.planet.works-simple')

</x-layout.main>
