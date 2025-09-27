<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $words */

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    <x-poetry.poetry-compare :life="$life" :poetry="$poetry" :words="$words" :llm-variants="$llmVariants" />

    <x-layout.header-second>the End</x-layout.header-second>

    <x-layout.container>
        <x-pages.life-nav :model="$life" />
    </x-layout.container>

</x-layout.main>
