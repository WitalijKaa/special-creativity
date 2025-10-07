<?php

/** @var array<string, array> $json */

?>
<x-layout.main title="Export">
    <x-layout.header-main>Planet export</x-layout.header-main>

    <x-form.submit :route="route('web.planet.export')" btn="Export to files"></x-form.submit>

    <x-layout.divider />

    <x-table.basic name="Export statistics" :columns="['Items name', 'Items count']">
        @foreach($json as $itemName => $count)
            <tr>
                <td>{{ ucfirst($itemName) }}</td>
                <td>{{ $count }}</td>
            </tr>
        @endforeach
    </x-table.basic>

    <x-layout.divider />

    <x-layout.container>
        <x-pages.major-nav />

        <x-layout.wrapper>
            <x-button.links :items="[
                ['cc' => CC_DANGER, 'route' => route('web.planet.export'), 'label' => 'Export'],
                ['cc' => CC_DANGER, 'route' => route('web.planet.import'), 'label' => 'Import'],
            ]" />
        </x-layout.wrapper>
    </x-layout.container>

</x-layout.main>
