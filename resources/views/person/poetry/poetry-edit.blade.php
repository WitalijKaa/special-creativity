<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $llmVariants */

$factory = new \App\Dto\Form\FormInputFactory();
$vPerson = new \App\Models\View\PersonView();

$mayFinalRepeat = $life->has_final_poetry && in_array($poetry->first()->llm, config('basic.final_flow'));

$llmName = $poetry->first()?->llm ?: 'Original text';

?><x-layout.main :title="$vPerson->titleLife($life) . ' edit Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    <x-layout.header-second>Edit {{$llmName}}</x-layout.header-second>

    @foreach($poetry as $paragraph)
        @if($llmVariants->count())
            <x-layout.container>
                <x-poetry.paragraphs-llm-variants :paragraph="$paragraph" :life="$life" :words="[]" :llm-variants="$llmVariants"/>
            </x-layout.container>
        @endif

        <div id="p_{{$paragraph->ix_text}}"></div>
        @php($fText = $factory->textarea('text', 'Paragraph #' . $paragraph->ix_text, $factory->withValue($paragraph->text)))

        <x-form.basic :route="route('web.person.poetry-paragraph-change', ['id' => $paragraph->id])"
                      btn="Save paragraph"
                      :textarea-rows="(mb_strlen($paragraph->text) / 110) < 8 ? 8 : (int)(mb_strlen($paragraph->text) / 110)"
                      :bottom-info="'Y ' . $paragraph->begin . ($paragraph->begin == $paragraph->end ? '' : ('-' . $paragraph->end))"
                      :fields="[$fText]"></x-form.basic>

        <x-layout.container>
            <x-button.links :items="[
                    ['cc' => CC_DARK, 'route' => route('web.person.poetry-life', ['life_id' => $life->id]), 'label' => 'back to Poetry'],
                    ['cc' => CC_PRIMARY, 'form' => true, 'route' => route('web.person.poetry-paragraph-delete', ['id' => $paragraph->id]), 'label' => 'Delete'],
                    ['cc' => CC_DANGER, 'route' => route('web.person.poetry-paragraph-move-down', ['id' => $paragraph->id]), 'label' => 'Move down'],
                ]" />
        </x-layout.container>

        <x-layout.divider />
    @endforeach

    <x-layout.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-layout.container>

</x-layout.main>
