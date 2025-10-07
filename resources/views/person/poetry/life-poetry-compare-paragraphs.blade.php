<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $wordsSlavic */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $wordsEnglish */

$words = [
    LL_RUS => $wordsSlavic,
    LL_ENG => $wordsEnglish,
];

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    <x-poetry.poetry :life="$life" :poetry="$poetry" :words="$words" :llm-variants="$llmVariants" />

    @if(!$poetry->count())
        <x-layout.header-second>no text provided for this Chapter</x-layout.header-second>
    @else
        <x-layout.header-second>the End</x-layout.header-second>
    @endif

    <x-layout.container>
        <x-pages.life-nav :model="$life" :very-previous="true" />
    </x-layout.container>

</x-layout.main>
