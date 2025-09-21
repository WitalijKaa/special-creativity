<?php

/** @var \App\Models\World\Life $life */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Poetry\Poetry[] $poetry */

$factory = new \App\Dto\Form\FormInputFactory();

?><x-layout.main :title="$life->person->name . ' ' . $life->type_name . '-' . $life->current_type_no">
    <x-layout.header-main>
        {{ $life->person->name }} {{ $life->person->nick }} {{ $life->role_name }}
        <br>
        [{{ $life->begin }}-{{ $life->end }}]Y<small><small>{{ $life->end - $life->begin }}</small></small>
        <br>
        {{ $life->type_name }}-{{ $life->current_type_no }}
    </x-layout.header-main>

    <x-layout.header-second>Edit paragraph (only a single one)</x-layout.header-second>

    <x-session.success></x-session.success>

    @foreach($poetry as $paragraph)
        @php($fText = $factory->withValue($paragraph->text)->textarea('text', 'Paragraph #' . $paragraph->ix_text))

        <x-form.basic :route="route('web.person.poetry-paragraph-change', ['id' => $paragraph->id])"
                      btn="Save paragraph"
                      :bottom-info="'Y ' . $paragraph->begin . ($paragraph->begin == $paragraph->end ? '' : ('-' . $paragraph->end))"
                      :fields="[$fText]"></x-form.basic>

        <x-form.submit :route="route('web.person.poetry-paragraph-delete', ['id' => $paragraph->id])" btn="del"></x-form.submit>
        <x-form.submit :route="route('web.person.poetry-paragraph-move-down', ['id' => $paragraph->id])" btn="move down"></x-form.submit>

        <x-layout.divider></x-layout.divider>
    @endforeach

    <x-layout.container>
        <a href="{{ route('web.person.poetry-life', ['life_id' => $life->id]) }}" class="btn btn-secondary btn-lg">Back to Poetry</a>
    </x-layout.container>
</x-layout.main>
