<?php

/** @var \App\Models\Poetry\PoetryWord $model */

$factory = new \App\Dto\Form\FormInputFactory();

$formUpdateWord = [
    $factory->input('word', $factory->withValue($model->word)),
    $factory->input('word_eng', 'in English', $factory->withValue($model->word_eng)),
    $factory->textarea('definition', $factory->withValue($model->definition)),
    $factory->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Language', $factory->withValue($model->lang)),
];
?>
<x-layout.main title="Edit poetry word">
    <x-layout.header-main>Edit poetry word</x-layout.header-main>

    <x-form.basic :route="route('web.person.poetry-word-change', ['id' => $model->id])"
                  btn="Update word"
                  :fields="$formUpdateWord"></x-form.basic>

    <x-form.container>
        @include('components.pages.major-nav')
        <br><br>
        <a href="{{route('web.person.poetry-words')}}" type="button" class="btn btn-danger btn-lg">Poetry words</a>
    </x-form.container>

</x-layout.main>
