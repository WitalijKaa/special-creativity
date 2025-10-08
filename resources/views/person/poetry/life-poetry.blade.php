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

/** @var \App\Models\Poetry\Poetry $llmFirstParagraph */

$originalLang = $poetry->first()?->lang == LL_RUS ? LL_RUS : LL_ENG;
$factory = new \App\Dto\Form\FormInputFactory();
$el = new \App\Dto\Form\FormInputFactory();

$formChapter = new \App\Models\View\FormBasicBuilder()
    ->route(route('web.person.chapter-add', ['life_id' => $life->id]), 'smart parse Chapter')
    ->add($el->textarea('chapter'))
    ->add($el->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Which language?'));

$toLang = $factory->select('to_lang', \App\Models\Poetry\LanguageHelper::selectOptions($originalLang), 'Into which language translate to?');
$formLlmTranslate = new \App\Models\View\FormBasicBuilder()
    ->secondColumn($el->select('llm', \App\Models\Poetry\Llm\LlmConfig::selectLlmOptions(), 'Which llm to use?'))
    ->add($el->select('llm_quality', \App\Models\Poetry\Llm\LlmConfig::selectQualityOptions(), 'Quality of llm calculations?'));

$formLlm = new \App\Models\View\FormBasicBuilder()
    ->add($el->select('llm', \App\Models\Poetry\Llm\LlmConfig::selectLlmOptions(), 'Which llm to use?'))
    ->add($el->select('llm_mode', \App\Models\Poetry\Llm\LlmConfig::selectModeOptions(), 'What kind of methodology to use?'))
    ->secondColumn($el->select('llm_rise_creativity', \App\Models\Poetry\Llm\LlmConfig::selectRiseCreativityOptions(), 'Should we rise creativity of llm?'))
    ->add($el->select('llm_quality', \App\Models\Poetry\Llm\LlmConfig::selectQualityOptions(), 'Quality of llm calculations?'));

$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    @if($poetry->count())

        <x-layout.container>
            <x-layout.wrapper>
                <x-button.link :cc="CC_SUCCESS" :route="route('web.person.poetry-life-compare-paragraphs', ['life_id' => $life->id])" label="Paragraph by paragraph analysis" />
            </x-layout.wrapper>
            <x-pages.life-nav :model="$life" :very-previous="true" />
        </x-layout.container>

        <x-layout.divider />

        <x-poetry.poetry :life="$life" :poetry="$poetry" :words="$words" />

        <x-layout.divider />

        <x-form.submit :route="route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'null'])" method="get" btn="Edit paragraphs"></x-form.submit>

        <x-layout.divider />

        <x-form.basic :form="$formLlmTranslate->formPrepend($toLang)
                                              ->route(route('web.person.chapter-translate', ['life_id' => $life->id]), 'do Translation')" />

        @if($poetry->count() && LL_ENG == $originalLang)
            <x-layout.divider />

            <x-form.basic :form="$formLlm->route(route('web.person.poetry-life-improve', ['life_id' => $life->id]), 'Improve Original text')" />
        @endif

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

            @if(LL_ENG == $llmFirstParagraph->lang)
                <x-layout.divider />
                <x-form.basic :form="$formLlm->formPrepend($fromLLM)
                                             ->route(route('web.person.poetry-life-improve', ['life_id' => $life->id]), 'Improve text')" />
                <x-layout.divider />
            @endif

            <x-form.basic :form="$formLlmTranslate->formPrepend([$fromLLM, $fromLang, $toLang])
                                                  ->route(route('web.person.chapter-translate', ['life_id' => $life->id]), 'Translate again')" />
        @endforeach

        <x-layout.divider />

    @endif

    @if($poetry->count())
        <x-layout.header-second>delete all and Start with new Chapter...</x-layout.header-second>
    @else
        <x-layout.header-second>add Chapter to begin...</x-layout.header-second>
    @endif

    <x-form.basic :form="$formChapter" />

    <x-layout.divider />

    <x-layout.container>
        <x-layout.wrapper>
            <x-button.link :cc="CC_SUCCESS" :route="route('web.person.poetry-life-compare-paragraphs', ['life_id' => $life->id])" label="Paragraph by paragraph analysis" />
        </x-layout.wrapper>
        <x-pages.life-nav :model="$life" :very-previous="true" />
    </x-layout.container>

</x-layout.main>
