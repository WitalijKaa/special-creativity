<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */

$factory = new \App\Dto\Form\FormInputFactory();
$vPerson = new \App\Models\View\PersonView();

?><x-layout.main :title="$vPerson->titleLife($life) . ' edit Poetry'">
    <x-pages.headers.life-header :model="$life"></x-pages.headers.life-header>

    <x-layout.header-second>Edit paragraph (only a single one)</x-layout.header-second>

    <x-session.success></x-session.success>

    @foreach($poetry as $paragraph)
        @php($fText = $factory->textarea('text', 'Paragraph #' . $paragraph->ix_text, $factory->withValue($paragraph->text)))

        <x-form.basic :route="route('web.person.poetry-paragraph-change', ['id' => $paragraph->id])"
                      btn="Save paragraph"
                      :bottom-info="'Y ' . $paragraph->begin . ($paragraph->begin == $paragraph->end ? '' : ('-' . $paragraph->end))"
                      :fields="[$fText]"></x-form.basic>

        <x-form.submit :route="route('web.person.poetry-paragraph-delete', ['id' => $paragraph->id])" btn="del"></x-form.submit>
        <x-form.submit :route="route('web.person.poetry-paragraph-move-down', ['id' => $paragraph->id])" btn="move down"></x-form.submit>

        <x-layout.divider></x-layout.divider>
    @endforeach

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-form.container>

</x-layout.main>
