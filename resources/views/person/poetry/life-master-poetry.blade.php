<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $wordsSlavic */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $wordsEnglish */

$words = [
    LL_RUS => $wordsSlavic,
    LL_ENG => $wordsEnglish,
];

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    <x-poetry.poetry :life="$life" :poetry="$poetry" :words="$words" />

    <x-layout.divider />

    <x-layout.container>
        <x-pages.life-nav :model="$life" :very-previous="true" />
    </x-layout.container>

</x-layout.main>
