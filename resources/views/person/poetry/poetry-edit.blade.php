<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */

$factory = new \App\Dto\Form\FormInputFactory();
$vPerson = new \App\Models\View\PersonView();

$mayFinalRepeat = $life->has_final_poetry && in_array($poetry->first()->llm, config('basic.final_flow'));

?><x-layout.main :title="$vPerson->titleLife($life) . ' edit Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    <x-layout.header-second>Edit paragraph (only a single one)</x-layout.header-second>

    @foreach($poetry as $paragraph)
        <div id="p_{{$paragraph->ix_text}}"></div>
        @php($fText = $factory->textarea('text', 'Paragraph #' . $paragraph->ix_text, $factory->withValue($paragraph->text)))

        <x-form.basic :route="route('web.person.poetry-paragraph-change', ['id' => $paragraph->id])"
                      btn="Save paragraph"
                      :textarea-rows="(mb_strlen($paragraph->text) / 110) < 8 ? 8 : (int)(mb_strlen($paragraph->text) / 110)"
                      :bottom-info="'Y ' . $paragraph->begin . ($paragraph->begin == $paragraph->end ? '' : ('-' . $paragraph->end))"
                      :fields="[$fText]"></x-form.basic>

{{--        <x-form.submit :route="route('web.person.poetry-paragraph-delete', ['id' => $paragraph->id])" btn="del"></x-form.submit>--}}
        <x-form.submit :route="route('web.person.poetry-paragraph-move-down', ['id' => $paragraph->id])" btn="move down"></x-form.submit>

{{--        <x-layout.container>--}}
{{--            @php($finalRepeat = $mayFinalRepeat ? [['cc' => CC_SUCCESS, 'route' => route('web.person.poetry-life', ['life_id' => $life->id]), 'label' => 'Repeat final']] : [])--}}
{{--            <x-button.links :items="array_merge([--}}
{{--                ['cc' => CC_DARK, 'route' => route('web.person.poetry-life', ['life_id' => $life->id]), 'label' => 'back to Poetry'],--}}
{{--                ['route' => route('web.person.poetry-paragraph-move-down', ['id' => $paragraph->id]), 'label' => 'Move Down'],--}}
{{--                ['route' => route('web.person.poetry-paragraph-delete', ['id' => $paragraph->id]), 'label' => 'Delete'],--}}
{{--            ], $finalRepeat)" />--}}
{{--        </x-layout.container>--}}

        <x-layout.divider />
    @endforeach

    <x-layout.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-layout.container>

</x-layout.main>
