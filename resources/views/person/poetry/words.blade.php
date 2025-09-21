<?php

/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\PoetryWord[] $models */

$factory = new \App\Dto\Form\FormInputFactory();

$formAddWord = [
    $factory->input('word', 'Word'),
    $factory->textarea('definition', 'Definition'),
    $factory->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Language'),
];

?>
<x-layout.main title="Poetry words">
    <x-layout.header-main>Poetry words</x-layout.header-main>

    <x-form.basic :route="route('web.person.poetry-word-add')"
                  btn="Save word"
                  :fields="$formAddWord"></x-form.basic>

    <x-layout.divider></x-layout.divider>

    <x-session.success></x-session.success>

    <x-table.basic name="Words" :columns="['Word', 'Definition', 'Actions']">
        @foreach($models as $word)
            <tr>
                <td>{{ $word->word }}</td>
                <td>{{ $word->definition }}</td>
                <td>
                    <a href="{{ route('web.person.poetry-word-edit', ['id' => $word->id]) }}" class="btn btn-primary btn-sm">edit</a>
                    <x-form.submit-nano :route="route('web.person.poetry-word-translate', ['id' => $word->id])" btn="translate"></x-form.submit-nano>
                    <x-form.submit-nano :route="route('web.person.poetry-word-delete', ['id' => $word->id])" btn="del"></x-form.submit-nano>
                </td>
            </tr>
        @endforeach
    </x-table.basic>

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        <a href="{{ route('web.planet.params') }}" type="button" class="btn btn-secondary btn-lg">Params</a>
        <a href="{{ route('web.person.list') }}" type="button" class="btn btn-primary btn-lg">Personas</a>
    </x-form.container>
</x-layout.main>
