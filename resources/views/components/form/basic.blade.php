<div class="container">
    <div class="row justify-content-center">
        <div class="col-10 col-md-8 col-lg-6">
            <form action="{{$route}}" method="post">
                @csrf
                @foreach($fields as $field)
                    @if ($field instanceof \App\Dto\Form\FormFieldInputDto && is_null($field->options))
                        <div class="mb-4">
                            <label for="{{$field->id}}" class="form-label">{{$field->label}}</label>
                            <input id="{{$field->id}}" name="{{$field->id}}" value="{{$field->value ?? old($field->id)}}" type="{{$field->type}}" class="form-control" aria-describedby="{{$field->id}}Tip">
                            @if(!empty($errors->get($field->id)[0]))
                                <div id="{{$field->id}}Tip" class="form-text text-danger">{{$errors->get($field->id)[0]}}</div>
                            @elseif(!empty($field->nonErrorTip))
                                <div id="loginTip" class="form-text">{{$field->nonErrorTip}}</div>
                            @endif
                        </div>
                    @elseif ($field instanceof \App\Dto\Form\FormFieldInputDto)
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
                @endforeach
                <div class="d-grid offset-md-8 col-md-4">
                    <button type="submit" class="btn btn-outline-success">{{$btn}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
