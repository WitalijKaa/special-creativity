<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry $paragraph */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $words */

$isNextWordTip = false;
$vPerson = new \App\Models\View\PersonView();

?><p>
    {{ $slot }}
    @php($spans = explode(' ', $paragraph->text))
    @foreach($spans as $ixW => $word)
        @if($isNextWordTip)
            @php($isNextWordTip = false)
        @else
            @php($nextWord = \App\Models\Poetry\Poetry::isEndingWord($word) || empty($spans[$ixW + 1]) ? null : $spans[$ixW + 1])
            @php([$wordClass, $wordTip, $isNextWordTip] = $vPerson->wordSpanClass($word, $words, $nextWord))

            @if($isNextWordTip)
                <span class="{{$wordClass}}">{{$word}} {{$nextWord}}</span>
            @else
                <span class="{{$wordClass}}">{{$word}}</span>
            @endif
        @endif
    @endforeach
</p>
