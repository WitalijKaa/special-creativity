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

$chapter = null;
$part = null;
$partName = $life->is_allods ? 'Аллоды' : 'Планета';
$firstPartTitled = false;

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    @if($poetry->count())

        <x-layout.container>
            @foreach($poetry as $paragraph)
                @php($pList = explode(' ', $paragraph->text))

                @if($chapter != $paragraph->chapter)
                    @php($chapter = $paragraph->chapter)
                    <h2 class="mb-4">ГЛАВА {{ $chapter }} @if($life->person_id != \App\Models\Person\Person::ORIGINAL) РАКУРС {{ $life->person_id }} {{ $life->person->name }} @endif</h2>
                @endif

                @if($part != $paragraph->part)
                    @php($part = $paragraph->part)
                    @php($aboutAllLife = $paragraph->begin == $life->begin && $paragraph->end == $life->end)
                    @php($isPartPhilosophy = $paragraph->spectrum == \App\Models\Poetry\Poetry::SPECTRUM_PHILOSOPHY)
                    @if(!$firstPartTitled || $aboutAllLife)
                        <h4 class="mb-4">РАЗДЕЛ {{ $partName }} {{ $isPartPhilosophy ? '(размышления)' : $vPerson->rusYears($life) }}</h4>
                    @endif
                    @if(!$aboutAllLife)
                        <h4 class="mb-4">ПОДРАЗДЕЛ {{ $partName }} {{ $vPerson->rusYears($paragraph) }}</h4>
                    @endif
                    @php($firstPartTitled = true)
                @endif

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

        <x-layout.divider />

        <x-form.submit :route="route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'null'])" method="get" btn="Edit paragraphs"></x-form.submit>

        <x-layout.divider />

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

        <x-layout.divider />

    @endif

    @if($poetry->count())
        <x-layout.header-second>delete all and Start with new Chapter...</x-layout.header-second>
    @else
        <x-layout.header-second>add Chapter and start Writing...</x-layout.header-second>
    @endif

    <x-form.basic :route="route('web.person.chapter-add', ['life_id' => $life->id])"
                  btn="smart parse chapter"
                  :fields="$formAddChapter"></x-form.basic>

    <x-layout.divider />

    @if($events->count())
        <x-layout.container>
            <x-pages.life-nav :model="$life" />
        </x-layout.container>

        <x-layout.divider />
    @endif

    @if($life->lifeWork->workYears)
        <x-layout.header-second>Work 💪🏻 is {{ $life->lifeWork->workYears }}</x-layout.header-second>
    @endif

    @if($events->count())
        <x-layout.header-second>Events of this Life</x-layout.header-second>
        <x-layout.container>
            @include('widgets.person.events', ['events' => $events, 'person' => $life->person, 'lifeWork' => $life->lifeWork])
        </x-layout.container>
    @endif

    <x-layout.container>
        <x-pages.life-nav :model="$life" />
    </x-layout.container>

</x-layout.main>
