<?php

/** @var \App\Models\View\FormBasicBuilder $form */

$colClass = $form->secondaryColumn ? 'col-6 col-lg-5' : 'col-12 col-md-10 col-lg-8';
$colClass = $form->thirdColumn ? 'col-4' : $colClass;

?><form class="container mb-4" action="{{$form->route}}" method="{{$form->method}}">
    @csrf
    <div class="row justify-content-center">
        <div class="{{$colClass}}">
            @foreach($form->mainColumn as $field)
                <x-form.elements.input :field="$field" />
            @endforeach
            @if (!$form->secondaryColumn && !$form->thirdColumn)
                <x-form.elements.submit :form="$form" />
            @endif
        </div>
        @if ($form->secondaryColumn)
            <div class="{{$colClass}}">
                @foreach($form->secondaryColumn as $field)
                    <x-form.elements.input :field="$field" :textarea-rows="$form->singleTextareaAtSecondColumnHeight()" />
                @endforeach
                @if (!$form->thirdColumn)
                    <x-form.elements.submit :form="$form" />
                @endif
            </div>
        @endif
        @if ($form->thirdColumn)
            <div class="{{$colClass}}">
                @foreach($form->thirdColumn as $field)
                    <x-form.elements.input :field="$field" />
                @endforeach

                <x-form.elements.submit :form="$form" />
            </div>
        @endif
    </div>
</form>
