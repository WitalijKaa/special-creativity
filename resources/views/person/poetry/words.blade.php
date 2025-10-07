<?php

/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\PoetryWord[] $slavic */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\PoetryWord[] $english */

$el = new \App\Dto\Form\FormInputFactory();

$form = new \App\Models\View\FormBasicBuilder()
    ->route(route('web.person.poetry-word-add'), 'Save word')
    ->add($el->input('word'))
    ->add($el->input('word_eng', 'English'))
    ->add($el->input('word_ai', 'AI'))
    ->add($el->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Language'))
    ->secondColumn($el->textarea('definition'));

?><x-layout.main title="Poetry words">
    <x-layout.header-main>Poetry words</x-layout.header-main>

    <x-form.basic :form="$form" />

    <x-session.app-msg :div-top="true" />

    <x-layout.header-second>Main slavic words</x-layout.header-second>

    <x-table.basic name="Words" :columns="['Word', 'English', 'AI', 'Definition', 'Actions']">
        @foreach($slavic as $word)
            <tr>
                <td>{{ $word->word }}</td>
                <td>{{ $word->word_eng }}</td>
                <td>{{ $word->word_ai }}</td>
                <td>{{ $word->definition }}</td>
                <td style="width: 120px;">
                    <a href="{{ route('web.person.poetry-word-edit', ['id' => $word->id]) }}" class="btn btn-primary btn-sm">edit</a>
                    <x-form.submit-nano :route="route('web.person.poetry-word-delete', ['id' => $word->id])" btn="del"></x-form.submit-nano>
                </td>
            </tr>
        @endforeach
    </x-table.basic>

    <x-layout.header-second>English specifics</x-layout.header-second>

    <x-table.basic name="Words" :columns="['Slavic', 'English', 'Searcher', 'Definition', 'Actions']">
        @foreach($english as $word)
            <tr>
                <td>{{ $word->word_ai }}</td>
                <td>{{ $word->word_eng }}</td>
                <td>{{ $word->word }}</td>
                <td>{{ $word->definition }}</td>
                <td style="width: 120px;">
                    <a href="{{ route('web.person.poetry-word-edit', ['id' => $word->id]) }}" class="btn btn-primary btn-sm">edit</a>
                    <x-form.submit-nano :route="route('web.person.poetry-word-delete', ['id' => $word->id])" btn="del"></x-form.submit-nano>
                </td>
            </tr>
        @endforeach
    </x-table.basic>

    <x-layout.divider />

    <x-layout.container>
        @include('components.pages.major-nav')
    </x-layout.container>

</x-layout.main>
