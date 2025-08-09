<?php

/** @var int $begin */
/** @var int $end */
/** @var \App\Models\Person\Person[] $models */

$fBegin = new \App\Dto\Form\FormFieldInputDto();
$fBegin->id = 'begin';
$fBegin->type = 'number';
$fBegin->value = old($fBegin->id) ?? $begin;
$fBegin->label = 'from Year';
$fEnd = new \App\Dto\Form\FormFieldInputDto();
$fEnd->id = 'end';
$fEnd->type = 'number';
$fEnd->value = old($fEnd->id) ?? $end;
$fEnd->label = 'until Year';

$vPerson = new \App\Models\View\PersonView();

$sizePX = 20;
$yearsBeforeBegin = 200;
$yearsAfterEnd = 200;

$yearOfDrawStart = $begin - $yearsBeforeBegin;
if ($yearOfDrawStart < 0) {
    $yearsBeforeBegin += $yearOfDrawStart;
    $yearOfDrawStart = 0;
}
$beforeSizePX = $sizePX * $yearsBeforeBegin;
$ten = $yearOfDrawStart + 10;
$length = $end - $begin + $yearsBeforeBegin + $yearsAfterEnd;
$yearOfDrawEnd = $end + $yearsAfterEnd - 20;

$titlePage = $models->count() . ' Lives-T';

?><x-layout.main :title="$titlePage">
    <x-layout.header-main>{{ $titlePage }}</x-layout.header-main>

    <x-form.basic :route="route('web.visual.lives-timeline')"
                  btn="show Range"
                  :btn-warn="['lbl' => 'Back', 'href' => route('web.visual.lives-timeline')]"
                  :fields="[$fBegin, $fEnd]"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <div style="height: {{ $length * $sizePX }}px; width: {{ $models->count() * $sizePX }}px; background-color: #cfd8dc; position: relative;">
        @foreach($models as $person)
            @foreach($person->lives as $life)
                @if($life->begin >= $yearOfDrawStart && $life->end <= $yearOfDrawEnd)
                    <div style="position: absolute;
                                background-color: {{ $vPerson->lifeBackColor($life) }};
                                width: {{ $sizePX - 4 }}px;
                                height: {{ ($life->end - $life->begin) * $sizePX - 4 }}px;
                                top: {{ 2 + ($life->begin - $yearOfDrawStart) * $sizePX }}px;
                                left: {{ 2 + ($person->id - 1) * $sizePX }}px;
                                color: white;
                                writing-mode: vertical-rl;
                                text-orientation: upright;
                                letter-spacing: -0.35rem;
                                line-height: 1.1rem;
                                border-radius: 4px;
                                box-sizing: border-box;">
                        {{ $vPerson->labelLifeShort($life) }}
                    </div>
                @endif
            @endforeach
        @endforeach

        @while($ten < ($end + $yearsAfterEnd - 5))
            <div style="position: absolute;
                        background-color: #1a237e;
                        width: 100%;
                        height: 2px;
                        top: {{ ($ten - $yearOfDrawStart) * $sizePX }}px;
                        left: 0;">
                {{ $ten }}Y
            </div>
            @php($ten += 10)
        @endwhile
    </div>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.persons-list-nav')
    </x-form.container>

</x-layout.main>
