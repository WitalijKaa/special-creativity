<?php

/** @var \App\Models\Poetry\Poetry $paragraph */
/** @var \App\Models\World\Life $life */
/** @var array<\App\Models\Collection\PoetryWordCollection> $words */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */

?>@foreach($llmVariants as $variation)
    @foreach($variation as $paragraphVariant)
        @if($paragraphVariant->part == $paragraph->part && $paragraphVariant->ix_text == $paragraph->ix_text)
            @php($pWords = $words[$paragraphVariant->lang] ?? new \Illuminate\Support\Collection())
            <x-poetry.paragraph :paragraph="$paragraphVariant" :life="$life" :words="$pWords">
                <span class="llm-text-title-label">&nbsp;&nbsp;{{ $paragraphVariant->llm }}:&nbsp;&nbsp;</span>&nbsp;&nbsp;&nbsp;&nbsp;
            </x-poetry.paragraph>
        @endif
    @endforeach
@endforeach
