<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $aiVariants */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $words */

$factory = new \App\Dto\Form\FormInputFactory();

$formAddChapter = [
    $factory->textarea('chapter'),
    $factory->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Which language?'),
];

$formTranslateChapter = [
    $factory->select('to_lang', \App\Models\Poetry\LanguageHelper::selectTranslateFromOriginalOptions(), 'Into which language translate to?'),
    $factory->select('llm', \App\Models\Poetry\LanguageHelper::selectAiOptions(), 'Which llm to use?'),
];

$vPerson = new \App\Models\View\PersonView();
$isNextWordTip = false;

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    @if($poetry->count())

        <x-layout.header-second>poetry of Life...</x-layout.header-second>

        <x-form.submit :route="route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'null'])" method="get" btn="Edit paragraphs"></x-form.submit>

        <x-layout.container>
            @foreach($poetry as $paragraph)
                @php($pList = explode(' ', $paragraph->text))
                <p>
                    @foreach($pList as $ixW => $word)
                        @if($isNextWordTip)
                            @php($isNextWordTip = false)
                        @else
                            @php($nextWord = \App\Models\Poetry\Poetry::isEndingWord($word) || empty($pList[$ixW + 1]) ? null : $pList[$ixW + 1])
                            @php([$wordClass, $wordTip, $isNextWordTip] = $vPerson->wordSpanClass($word, $words, $nextWord))

                            @if($isNextWordTip)
                                <span class="{{$wordClass}}">{{$word}} {{$nextWord}}</span>
                            @else
                                <span class="{{$wordClass}}">{{$word}}</span>
                            @endif
                        @endif
                    @endforeach
                </p>
            @endforeach
        </x-layout.container>

        <x-form.basic :route="route('web.person.chapter-translate', ['life_id' => $life->id])"
                      btn="Translate to Foreign language"
                      :fields="$formTranslateChapter"></x-form.basic>

        @foreach($aiVariants as $variation)
            @php($vModel = $variation->first())
            <x-layout.header-second>
                {{ \App\Models\Poetry\LanguageHelper::label($vModel->lang) }}
                vs LLM
                <span class="badge bg-success">{{ $vModel->ai }}</span>
            </x-layout.header-second>

            <x-form.submit :route="route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $vModel->lang, 'llm' => $vModel->ai])" method="get" btn="Edit paragraphs"></x-form.submit>
            <x-form.submit :color="CC_DANGER" :route="route('web.person.poetry-life-delete', ['life_id' => $life->id, 'lang' => $vModel->lang, 'llm' => $vModel->ai])" method="get" btn="Kill paragraphs"></x-form.submit>

            <x-layout.container>
                @foreach($variation as $paragraph)
                    <p>{{$paragraph->text}}</p>
                @endforeach
            </x-layout.container>
        @endforeach

        <x-layout.divider></x-layout.divider>

    @endif

    <x-form.basic :route="route('web.person.chapter-add', ['life_id' => $life->id])"
                  btn="smart parse chapter"
                  :fields="$formAddChapter"></x-form.basic>

    <x-layout.header-second>Work 💪🏻 is {{ $life->lifeWork->workYears }}</x-layout.header-second>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'person' => $life->person, 'lifeWork' => $life->lifeWork])
    </x-layout.container>

    <x-layout.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-layout.container>

</x-layout.main>
