<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Support\Collection|\App\Models\Person\PersonEvent[] $events */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[][] $aiVariants */

$factory = new \App\Dto\Form\FormInputFactory();

$formAddChapter = [
    $factory->textarea('chapter'),
    $factory->select('lang', \App\Models\Poetry\LanguageHelper::selectOptions(), 'Which language?'),
];

$formTranslateChapter = [
    $factory->select('to_lang', \App\Models\Poetry\LanguageHelper::selectTranslateFromOriginalOptions(), 'Into which language translate to?'),
    $factory->select('llm', \App\Models\Poetry\LanguageHelper::selectAiOptions(), 'Which llm to use?'),
];

?><x-layout.main :title="$life->person->name . ' ' . $life->type_name . '-' . $life->current_type_no">
    <x-layout.header-main>
        {{ $life->person->name }} {{ $life->person->nick }} {{ $life->role_name }}
        <br>
        [{{ $life->begin }}-{{ $life->end }}]Y<small><small>{{ $life->end - $life->begin }}</small></small>
        <br>
        {{ $life->type_name }}-{{ $life->current_type_no }}
    </x-layout.header-main>

    <x-layout.header-second>poetry of Life...</x-layout.header-second>

    <x-layout.container>
        @foreach($poetry as $paragraph)
            <p>{{$paragraph->text}}</p>
        @endforeach
    </x-layout.container>

    <x-form.basic :route="route('web.person.chapter-translate', ['life_id' => $life->id])"
                  btn="Translate to Foreign language"
                  :fields="$formTranslateChapter"></x-form.basic>

    @foreach($aiVariants as $variation)
        @php($vModel = $variation->first())
        <x-layout.header-second>
            {{ \App\Models\Poetry\LanguageHelper::label($vModel->lang) }}
            vs LLM
            <span class="badge bg-success">{{ $vModel->ai }}</span>
        </x-layout.header-second>

        <x-layout.container>
            @foreach($variation as $paragraph)
                <p>{{$paragraph->text}}</p>
            @endforeach
        </x-layout.container>
    @endforeach

    <x-layout.divider></x-layout.divider>

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-form.container>

    <x-layout.divider></x-layout.divider>

    <x-form.basic :route="route('web.person.chapter-add', ['life_id' => $life->id])"
                  btn="smart parse chapter"
                  :fields="$formAddChapter"></x-form.basic>

    <x-layout.header-second>Work 💪🏻 is {{ $life->lifeWork->workYears }}</x-layout.header-second>

    <x-layout.container>
        @include('widgets.person.events', ['events' => $events, 'person' => $life->person, 'lifeWork' => $life->lifeWork])
    </x-layout.container>

    <x-form.container>
        @include('components.pages.life-nav', ['model' => $life])
    </x-form.container>

</x-layout.main>
