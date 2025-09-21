<?php

/** @var \App\Models\Poetry\PoetryWord $model */

$factory = new \App\Dto\Form\FormInputFactory();

$formUpdateWord = [
    $factory->withValue($model->word)->input('word', 'Word'),
    $factory->withValue($model->definition)->textarea('definition', 'Definition'),
    $factory->withValue($model->lang)->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Language'),
];
?>
<x-layout.main title="Edit poetry word">
    <x-layout.header-main>Edit poetry word</x-layout.header-main>

    <x-form.basic :route="route('web.person.poetry-word-change', ['id' => $model->id])"
                  btn="Update word"
                  :fields="$formUpdateWord"
                  :btnWarn="['href' => route('web.person.poetry-words'), 'lbl' => 'Back to list']"></x-form.basic>

</x-layout.main>
