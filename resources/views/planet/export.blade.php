<?php

/** @var array<string, array> $json */

?>
<x-layout.main title="Export">
    <x-layout.header-main>Planet export</x-layout.header-main>

    <x-session.success></x-session.success>
    <x-form.submit :route="route('web.planet.export')" btn="Export to file"></x-form.submit>

    <x-layout.divider></x-layout.divider>

    <x-table.basic name="Export statistics" :columns="['Items name', 'Items count']">
        @foreach($json as $itemName => $count)
            @if($items instanceof \Illuminate\Support\Collection)
                <tr>
                    <td>{{ ucfirst($itemName) }}</td>
                    <td>{{ $count }}</td>
                </tr>
            @endif
        @endforeach
    </x-table.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
        <a href="{{route('web.person.list')}}" type="button" class="btn btn-primary btn-lg">Personas</a>
    </x-form.container>
</x-layout.main>
