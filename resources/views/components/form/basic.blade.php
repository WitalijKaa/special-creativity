<?php

/** @var int $textareaRows */

$textareaRows ??= 8;

?>@if (!empty($form) && $form instanceof \App\Models\View\FormBasicBuilder)
    <x-form.basic-smart :form="$form" />
@else

<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="@if(!empty($bottomInfo)) col-12 @else col-10 col-md-8 col-lg-6 @endif">
            <form action="{{$route}}" method="{{empty($method) ? 'post' : $method}}">
                @csrf
                @foreach($fields as $field)
                    <x-form.elements.input :field="$field" :textarea-rows="$textareaRows" />
                @endforeach
                @if(!empty($bottomInfo))
                    <div class="d-grid col-xl-4">
                        <div class="text-muted small">{{ $bottomInfo }}</div>
                    </div>
                @endif
                <div class="d-grid offset-xl-8 col-xl-4">
                    <div class="d-flex @if(!empty($btnWarn)) justify-content-between @else justify-content-end @endif">
                        @if(!empty($btnWarn))
                            <a href="{{$btnWarn['href']}}" class="btn btn-outline-warning me-2">{{$btnWarn['lbl']}}</a>
                        @endif
                        <button type="submit" class="btn btn-outline-success">{{$btn}}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endif
