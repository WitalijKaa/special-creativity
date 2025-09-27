<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $words */
/** @var null|\Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */
$llmVariants ??= null;

$viewMemory = new stdClass();
$viewMemory->chapter = null;
$viewMemory->part = null;
$viewMemory->firstPartTitled = null;

?>
<x-layout.container>
    @foreach($poetry as $paragraph)
        <x-poetry.part-title :memory="$viewMemory" :paragraph="$paragraph" :life="$life" :show-division="!!$llmVariants"/>

        <x-poetry.paragraph :paragraph="$paragraph" :life="$life" :words="$words"/>

        @if($llmVariants)
            <x-poetry.paragraphs-llm-variants :paragraph="$paragraph" :life="$life" :words="$words" :llm-variants="$llmVariants"/>
            <x-layout.divider/>
        @endif
    @endforeach
</x-layout.container>
