<?php

/** @var \App\Dto\Form\FormFieldInputDto $field */
/** @var int $textareaRows */

$textareaRows ??= 8;

?>@if ($field->type == 'hidden')
    <input type="hidden" id="{{$field->id}}" name="{{$field->id}}" value="{{$field->value}}" autocomplete="off">

@elseif ($field->type == 'textarea')
    <div class="mb-4">
        <label for="{{$field->id}}" class="form-label">{{$field->label}}</label>
        <textarea rows="{{$textareaRows}}" id="{{$field->id}}" name="{{$field->id}}" class="form-control">{{ $field->value ?? old($field->id) }}</textarea>
        @if(!empty($errors->get($field->id)[0]))
            <div id="{{$field->id}}Tip" class="form-text text-danger">{{$errors->get($field->id)[0]}}</div>
        @elseif(!empty($field->nonErrorTip))
            <div id="loginTip" class="form-text">{{$field->nonErrorTip}}</div>
        @endif
    </div>
@elseif ($field->type == 'checkbox')
    <div class="form-check">
        <input id="{{$field->id}}" name="{{$field->id}}" class="form-check-input" type="checkbox">
        <label class="form-check-label" for="{{$field->id}}">
            {{$field->label}}
        </label>
    </div>
@elseif (is_null($field->options))
    <div class="mb-4">
        <label for="{{$field->id}}" class="form-label">{{$field->label}}</label>
        <input id="{{$field->id}}" name="{{$field->id}}" value="{{$field->value ?? old($field->id)}}" type="{{$field->type}}" class="form-control">
        @if(!empty($errors->get($field->id)[0]))
            <div id="{{$field->id}}Tip" class="form-text text-danger">{{$errors->get($field->id)[0]}}</div>
        @elseif(!empty($field->nonErrorTip))
            <div id="loginTip" class="form-text">{{$field->nonErrorTip}}</div>
        @endif
    </div>
@else
    @php($selected = $field->value ?? old($field->id) ?? $field->options[0]['opt'])
    <div class="mb-4">
        <label for="{{$field->id}}" class="form-label">{{$field->label}}</label>
        <select id="{{$field->id}}" name="{{$field->id}}" class="form-select mb-4">
            @foreach($field->options as $opt)
                <option value="{{$opt['opt']}}" @if($opt['opt'] == $selected) selected @endif>{{$opt['lbl']}}</option>
            @endforeach
        </select>
        @if(!empty($errors->get($field->id)[0]))
            <div id="{{$field->id}}Tip" class="form-text text-danger">{{$errors->get($field->id)[0]}}</div>
        @elseif(!empty($field->nonErrorTip))
            <div id="loginTip" class="form-text">{{$field->nonErrorTip}}</div>
        @endif
    </div>
@endif
