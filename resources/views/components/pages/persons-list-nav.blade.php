@if(request()->get('sort'))
    <a href="{{route('web.person.list')}}" type="button" class="btn btn-outline-primary btn-lg">Basic</a>
@endif
@if(request()->get('sort') != 'last_year')
    <a href="{{route('web.person.list', ['sort' => 'last_year'])}}" type="button" class="btn btn-outline-primary btn-lg">Soon time</a>
@endif
@if(request()->get('sort') != 'desc_last_year')
    <a href="{{route('web.person.list', ['sort' => 'desc_last_year'])}}" type="button" class="btn btn-outline-primary btn-lg">Far time</a>
@endif
