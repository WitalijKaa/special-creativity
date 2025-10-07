<?php

/** @var \App\Models\View\FormBasicBuilder $form */

$colClass = $form->secondaryColumn ? 'col-6 col-lg-5' : 'col-10 col-md-8 col-lg-6';

?><form class="container mb-4" action="{{$form->route}}" method="{{$form->method}}">
    @csrf
    <div class="row justify-content-center">
        <div class="{{$colClass}}">
            @foreach($form->mainColumn as $field)
                <x-form.form-input :field="$field" />
            @endforeach
            @if (!$form->secondaryColumn)
                <div class="col-6 offset-6">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">{{$form->label}}</button>
                    </div>
                </div>
            @endif
        </div>
        @if ($form->secondaryColumn)
            <div class="{{$colClass}}">
                @foreach($form->secondaryColumn as $field)
                    <x-form.form-input :field="$field" :textarea-rows="$form->singleTextareaAtSecondColumnHeight()" />
                @endforeach

                <div class="col-6 offset-6">
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-outline-success">{{$form->label}}</button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</form>
