<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $wordsSlavic */
/** @var \App\Models\Collection\PoetryWordCollection|\App\Models\Poetry\PoetryWord[] $wordsEnglish */
/** @var \Illuminate\Support\Collection<\App\Models\Poetry\Poetry> $llmAllNames */

$words = [
    LL_RUS => $wordsSlavic,
    LL_ENG => $wordsEnglish,
];

if ($life->master_poetry) {
    $analysis[] = ['cc' => CC_PRIMARY, 'route' => route('web.person.master-poetry', ['life_id' => $life->id]), 'label' => 'MASTER Poetry'];
}
if ($life->has_final_poetry) {
    $analysis[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.poetry-life-compare-paragraphs', ['life_id' => $life->id]), 'label' => 'Final analysis'];
}
if ($life->has_alpha_poetry) {
    $analysis[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.poetry-life-compare-paragraphs-alpha', ['life_id' => $life->id]), 'label' => 'ALPHA BETA analysis'];
}
$analysis[] = ['cc' => CC_DANGER, 'route' => route('web.person.poetry-life-compare-paragraphs-tech', ['life_id' => $life->id]), 'label' => 'Paragraph by paragraph analysis'];
$analysis[] = ['cc' => CC_WARNING, 'route' => route('web.person.poetry-life-tech', ['life_id' => $life->id]), 'label' => 'View all texts'];

$making = [];
if (!$life->has_translated_poetry) {
    $making[] = ['cc' => CC_DARK, 'form' => true, 'route' => route('web.person.poetry-life-translate', ['life_id' => $life->id]), 'label' => 'Translate to ENG'];
}
if ($life->has_translated_poetry) {
    $making[] = ['cc' => CC_DARK, 'form' => true, 'route' => route('web.person.poetry-life-versions', ['life_id' => $life->id]), 'label' => 'reMake VER.s'];
    foreach (V_MAIN as $specific) {
        $making[] = ['cc' => CC_DARK, 'form' => true, 'route' => route('web.person.poetry-life-versions', ['life_id' => $life->id, 'specific' => $specific]), 'label' => 'reMake ' . $specific];
    }
}
$makingTranslate = [];
foreach (V_MAIN as $specific) {
    if ($llmAllNames->filter(fn (\App\Models\Poetry\Poetry $vPoetry) => $vPoetry->llm == $specific)->count()) {
        $makingTranslate[] = ['cc' => CC_SECONDARY, 'form' => true, 'route' => route('web.person.poetry-life-translate-again', ['life_id' => $life->id, 'specific' => $specific]), 'label' => 'make.Slavic ' . $specific];
    }
}
if (count($makingTranslate) == count(V_MAIN)) {
    $making[] = ['cc' => CC_SECONDARY, 'form' => true, 'route' => route('web.person.poetry-life-translate-again', ['life_id' => $life->id]), 'label' => 'Slavic VER.s'];
}
$making = array_merge($making, $makingTranslate);

$editAlpha = [];
if ($poetry->count()) {
    $editAlpha[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'a-null']), 'label' => 'Edit original'];
}
$editFinals = [];
$editMaster = [];
foreach ($llmAllNames as $variantPoetry) {
    if (V_TRANSLATION == $variantPoetry->llm) {
        $editAlpha[] = ['cc' => CC_INFO, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $variantPoetry->lang, 'llm' => $variantPoetry->llm]), 'label' => 'Edit ' . $variantPoetry->llm];
    }
    foreach (V_MAIN as $llmName) {
        if ($llmName == $variantPoetry->llm && LL_ENG == $variantPoetry->lang) {
            $editAlpha[] = ['cc' => CC_SECONDARY, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $variantPoetry->lang, 'llm' => $variantPoetry->llm]), 'label' => 'Edit ' . $variantPoetry->llm];
        }
    }
    foreach (V_MAIN as $llmName) {
        if ($llmName == $variantPoetry->llm && LL_RUS == $variantPoetry->lang) {
            $editAlpha[] = ['cc' => CC_INFO, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $variantPoetry->lang, 'llm' => $variantPoetry->llm]), 'label' => 'Edit ' . $variantPoetry->llm];
        }
    }

    if (str_starts_with($variantPoetry->llm, FINAL_LLM . '_')) {
        $editFinals[] = ['route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $variantPoetry->lang, 'llm' => $variantPoetry->llm]), 'label' => $variantPoetry->llm];
    }
    if (str_starts_with($variantPoetry->llm, MASTER)) {
        $editMaster[] = ['route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $variantPoetry->lang, 'llm' => $variantPoetry->llm]), 'label' => $variantPoetry->llm];
    }
}
if ($editFinals) {
    $editFinals = [['dropdown' => ['label' => 'Edit finals', 'cc' => CC_SUCCESS, 'items' => $editFinals]]];
}
if ($editMaster) {
    $editMaster = [['dropdown' => ['label' => 'Edit master', 'cc' => CC_SUCCESS, 'items' => $editMaster]]];
}

/** @var \App\Models\Poetry\Poetry $llmFirstParagraph */

$originalLang = $poetry->first()?->lang == LL_RUS ? LL_RUS : LL_ENG;
$el = new \App\Dto\Form\FormInputFactory();

$formChapter = new \App\Models\View\FormBasicBuilder()
    ->route(route('web.person.chapter-add', ['life_id' => $life->id]), 'smart parse Chapter')
    ->add($el->textarea('chapter'))
    ->add($el->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Which language?'));

$formLlmFinal = null;
if ($life->finalSlavicPoetry()) {
    $formLlmFinal = new \App\Models\View\FormBasicBuilder()
        ->route(route('web.person.poetry-life-finale', ['life_id' => $life->id]), 'Create final Script')
        ->add($el->select('llm', \App\Models\Poetry\Llm\LlmConfig::selectLlmOptions(config('basic.llm_models_final')), 'Which llm to use?'))
        ->secondColumn($el->select('llm_quality', \App\Models\Poetry\Llm\LlmConfig::selectQualityOptions(), 'Quality of llm calculations?'));
    if ($life->finalSlavicPoetry(true)) {
        $formLlmFinal->add($el->checkbox('emotions', 'Should it be more emotional?'));
    }
}

$toLang = $el->select('to_lang', \App\Models\Poetry\LanguageHelper::selectOptions($originalLang), 'Into which language translate to?');
$formLlmTranslate = new \App\Models\View\FormBasicBuilder()
    ->secondColumn($el->select('llm', \App\Models\Poetry\Llm\LlmConfig::selectLlmOptions(), 'Which llm to use?'))
    ->add($el->select('llm_quality', \App\Models\Poetry\Llm\LlmConfig::selectQualityOptions(), 'Quality of llm calculations?'));

$formLlmTranslateToSlavic = new \App\Models\View\FormBasicBuilder()
    ->secondColumn($el->select('llm', \App\Models\Poetry\Llm\LlmConfig::selectLlmOptions(config('basic.llm_models_to_slavic')), 'Which llm to use?'))
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
                <x-button.links :items="$analysis" />
            </x-layout.wrapper>
            @if($making)
                <x-layout.wrapper>
                    <x-button.links :items="$making" />
                </x-layout.wrapper>
            @endif
            <x-layout.wrapper>
                <x-button.links :items="array_merge($editAlpha, $editFinals, $editMaster)" />
            </x-layout.wrapper>
            <x-pages.life-nav :model="$life" :very-previous="true" />
        </x-layout.container>

        @foreach($llmVariants as $llmVariant)
            @php($llmFirstParagraph = $llmVariant->first())
            @continue(!str_contains($llmFirstParagraph->llm, FINAL_LLM))
            <x-layout.header-second><span class="badge bg-success">{{ $llmFirstParagraph->llm }}</span></x-layout.header-second>

            <x-layout.container>
                <x-button.links :items="[
                    ['cc' => CC_PRIMARY, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm]), 'label' => 'Edit paragraphs'],
                    ['cc' => CC_DANGER, 'form' => true, 'route' => route('web.person.poetry-life-delete', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm]), 'label' => 'Kill paragraphs'],
                    ['cc' => CC_SUCCESS, 'form' => true, 'route' => route('web.person.poetry-life-master', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm]), 'label' => 'Create master version'],
                ]" />
            </x-layout.container>

            <x-poetry.poetry :life="$life" :poetry="$llmVariant" :words="$words" />

        @endforeach

        @if($formLlmFinal)
            <x-layout.header-second>Now the Final Script may be created!</x-layout.header-second>
            <x-form.basic :form="$formLlmFinal" />
            <x-layout.divider />
        @else
            <x-layout.divider />
        @endif

        <x-layout.container>
            <x-button.links :items="[
                ['cc' => CC_PRIMARY, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'a-null']), 'label' => 'Edit original'],
                ['cc' => CC_SUCCESS, 'form' => true, 'route' => route('web.person.poetry-life-master', ['life_id' => $life->id, 'lang' => LL_RUS, 'llm' => 'a-null']), 'label' => 'Create master version'],
            ]" />
        </x-layout.container>


        <x-poetry.poetry :life="$life" :poetry="$poetry" :words="$words" />


        <x-layout.divider />

        <x-form.basic :form="$formLlmTranslate->formPrepend($toLang)
                                              ->route(route('web.person.chapter-translate', ['life_id' => $life->id]), 'do Translation')" />

        @if($poetry->count() && LL_ENG == $originalLang)
            <x-layout.divider />

            <x-form.basic :form="$formLlm->route(route('web.person.poetry-life-improve', ['life_id' => $life->id]), 'Improve Original text')" />
        @endif

        @foreach($llmVariants as $llmVariant)
            @php($llmFirstParagraph = $llmVariant->first())
            @continue(str_contains($llmFirstParagraph->llm, FINAL_LLM))
            <x-layout.header-second>
                {{ \App\Models\Poetry\LanguageHelper::label($llmFirstParagraph->lang) }}
                vs LLM
                <span class="badge bg-danger">{{ $llmFirstParagraph->llm }}</span>
            </x-layout.header-second>

            <x-layout.container>
                <x-button.links :items="[
                    ['cc' => CC_PRIMARY, 'route' => route('web.person.poetry-life-edit', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm]), 'label' => 'Edit paragraphs'],
                    ['cc' => CC_DANGER, 'form' => true, 'route' => route('web.person.poetry-life-delete', ['life_id' => $life->id, 'lang' => $llmFirstParagraph->lang, 'llm' => $llmFirstParagraph->llm]), 'label' => 'Kill paragraphs'],
                ]" />
            </x-layout.container>

            <x-poetry.poetry :life="$life" :poetry="$llmVariant" :words="$words" />

            @php($toLang = $el->select('to_lang', \App\Models\Poetry\LanguageHelper::selectOptions($llmFirstParagraph->lang), 'Into which language translate to?'))
            @php($fromLLM = $el->hidden('from_llm', $el->withValue($llmFirstParagraph->llm)))
            @php($fromLang = $el->hidden('from_lang', $el->withValue($llmFirstParagraph->lang)))

            @if(LL_ENG == $llmFirstParagraph->lang)
                <x-layout.divider />
                <x-form.basic :form="$formLlm->formPrepend($fromLLM)
                                             ->route(route('web.person.poetry-life-improve', ['life_id' => $life->id]), 'Improve text')" />
                <x-layout.divider />

                <x-form.basic :form="$formLlmTranslateToSlavic->formPrepend([$fromLLM, $fromLang, $toLang])
                                                              ->route(route('web.person.chapter-translate', ['life_id' => $life->id]), 'Translate again')" />
            @else
                <x-form.basic :form="$formLlmTranslate->formPrepend([$fromLLM, $fromLang, $toLang])
                                                      ->route(route('web.person.chapter-translate', ['life_id' => $life->id]), 'Translate again')" />
            @endif

        @endforeach

        <x-layout.divider />

        <x-layout.header-second>delete all and Start with new Chapter...</x-layout.header-second>
    @else
        <x-layout.header-second>add Chapter to begin...</x-layout.header-second>
    @endif

    <x-form.basic :form="$formChapter" />

    <x-layout.divider />

    <x-layout.container>
        <x-pages.life-nav :model="$life" :very-previous="true" />
    </x-layout.container>

</x-layout.main>
