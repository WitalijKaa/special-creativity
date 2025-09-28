<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $words */

/** @var \App\Models\Poetry\Poetry $llmFirstParagraph */

$factory = new \App\Dto\Form\FormInputFactory();

$formAddChapter = [
    $factory->textarea('chapter'),
    $factory->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Which language?'),
];

$toLang = $factory->select('to_lang', \App\Models\Poetry\LanguageHelper::selectOptions(LL_RUS), 'Into which language translate to?');
$formTranslateChapter = [
    $factory->select('llm', \App\Models\Poetry\Llm\LlmConfig::selectLlmOptions(), 'Which llm to use?'),
    $factory->select('llm_mode', \App\Models\Poetry\Llm\LlmConfig::selectModeOptions(), 'What kind of methodology to use?'),
    $factory->select('llm_quality', \App\Models\Poetry\Llm\LlmConfig::selectQualityOptions(), 'Quality of llm calculations?'),
    $factory->select('llm_rise_creativity', \App\Models\Poetry\Llm\LlmConfig::selectRiseCreativityOptions(), 'Should we rise creativity of llm?'),
];

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    @if($poetry->count())

        <x-layout.container>
            <x-layout.wrapper>
                <x-button.link :cc="CC_SUCCESS" :route="route('web.person.poetry-life-compare-paragraphs', ['life_id' => $life->id])" label="Paragraph by paragraph analysis" />
            </x-layout.wrapper>
            <x-pages.life-nav :model="$life" />
        </x-layout.container>

        <x-layout.divider />

        <x-poetry.poetry :life="$life" :poetry="$poetry" :words="$words" />

        <x-layout.divider />

        <x-form.submit :route="route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'null'])" method="get" btn="Edit paragraphs"></x-form.submit>

        <x-layout.divider />

        <x-form.basic :route="route('web.person.chapter-translate', ['life_id' => $life->id])"
                      btn="Translate to Foreign language"
                      :fields="array_merge([$toLang], $formTranslateChapter)"></x-form.basic>

        @foreach($llmVariants as $llmVariant)
            @php($llmFirstParagraph = $llmVariant->first())
            <x-layout.header-second>
                {{ \App\Models\Poetry\LanguageHelper::label($llmFirstParagraph->lang) }}
                vs LLM
                <span class="badge bg-success">{{ $llmFirstParagraph->llm }}</span>
            </x-layout.header-second>

            <x-form.submit :route="route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm])" method="get" btn="Edit paragraphs"></x-form.submit>
            <x-form.submit :color="CC_DANGER" :route="route('web.person.poetry-life-delete', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm])" method="get" btn="Kill paragraphs"></x-form.submit>

            <x-poetry.poetry :life="$life" :poetry="$llmVariant" :words="$words" />

            @php($toLang = $factory->select('to_lang', \App\Models\Poetry\LanguageHelper::selectOptions($llmFirstParagraph->lang), 'Into which language translate to?'))
            @php($fromLLM = $factory->hidden('from_llm', $factory->withValue($llmFirstParagraph->llm)))
            @php($fromLang = $factory->hidden('from_lang', $factory->withValue($llmFirstParagraph->lang)))
            <x-form.basic :route="route('web.person.chapter-translate', ['life_id' => $life->id])"
                          btn="Translate again"
                          :fields="array_merge([$fromLLM, $fromLang, $toLang], $formTranslateChapter)"></x-form.basic>
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
        <x-layout.wrapper>
            <x-button.link :cc="CC_SUCCESS" :route="route('web.person.poetry-life-compare-paragraphs', ['life_id' => $life->id])" label="Paragraph by paragraph analysis" />
        </x-layout.wrapper>
        <x-pages.life-nav :model="$life" />
    </x-layout.container>

</x-layout.main>
