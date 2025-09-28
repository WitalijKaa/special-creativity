<?php

/** @var \App\Models\Poetry\Poetry $paragraph */
/** @var \App\Models\World\Life $life */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $words */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */

?>@foreach($llmVariants as $variation)
    @foreach($variation as $paragraphVariant)
        @if($paragraphVariant->part == $paragraph->part && $paragraphVariant->ix_text == $paragraph->ix_text)
            <x-poetry.paragraph :paragraph="$paragraphVariant" :life="$life" :words="$words">
                <span class="llm-text-title-label">&nbsp;&nbsp;{{ $paragraphVariant->llm }}:&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
            </x-poetry.paragraph>
        @endif
    @endforeach
@endforeach
