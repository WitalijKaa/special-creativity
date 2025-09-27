<?php

/** @var \App\Models\Poetry\Poetry $paragraph */
/** @var \App\Models\World\Life $life */
/** @var \stdClass $memory */

$naming = [
    'rus' => [
        'Allods' => 'Аллоды',
        'Planet' => 'Планета',
        'thinking' => '(размышления)',
        'chapter' => 'ГЛАВА',
        'pointOfView' => 'РАКУРС',
        'part' => 'РАЗДЕЛ',
        'subpart' => 'ПОДРАЗДЕЛ',
    ],
    'eng' => [
        'Allods' => 'Allods',
        'Planet' => 'Planet',
        'thinking' => '(philosophy)',
        'chapter' => 'CHAPTER',
        'pointOfView' => 'ANGLE',
        'part' => 'PART',
        'subpart' => 'SUBPART',
    ],
];
$lang = array_key_exists($paragraph->lang, $naming) ? $paragraph->lang : LL_RUS;
/** @var \App\Models\Poetry\Poetry $paragraph */
$nThinking = $naming[$lang]['thinking'];
$nChapter = $naming[$lang]['chapter'];
$nPointOfView = $naming[$lang]['pointOfView'];
$nPart = $naming[$lang]['part'];
$nSubPart = $naming[$lang]['subpart'];
$partName = $life->is_allods ? $naming[$lang]['Allods'] : $naming[$paragraph->lang]['Planet'];

$vPerson = new \App\Models\View\PersonView();

?>@php($someTitle = false)
@if($memory->chapter != $paragraph->chapter)
    @php($memory->chapter = $paragraph->chapter)
    <h2 class="mb-4">{{ $nChapter }} {{ $memory->chapter }} @if($life->person_id != \App\Models\Person\Person::ORIGINAL) {{ $nPointOfView }} {{ $life->person_id }} {{ $life->person->name }} @endif</h2>
    @php($someTitle = true)
@endif

@if($memory->part != $paragraph->part)
    @php($memory->part = $paragraph->part)
    @php($aboutAllLife = $paragraph->begin == $life->begin && $paragraph->end == $life->end)
    @php($isPartPhilosophy = $paragraph->spectrum == \App\Models\Poetry\Poetry::SPECTRUM_PHILOSOPHY)
    @if(!$memory->firstPartTitled || $aboutAllLife)
        <h4 class="mb-4">{{ $nPart }} {{ $partName }} {{ $isPartPhilosophy ? $nThinking : $vPerson->rusYears($life) }}</h4>
        @php($someTitle = true)
    @endif
    @if(!$aboutAllLife)
        <h4 class="mb-4">{{ $nSubPart }} {{ $partName }} {{ $vPerson->rusYears($paragraph) }}</h4>
        @php($someTitle = true)
    @endif
    @php($memory->firstPartTitled = true)
@endif

@if($showDivision && $someTitle)
    <x-layout.divider></x-layout.divider>
@endif
