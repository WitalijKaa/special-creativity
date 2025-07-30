<a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
<a href="{{route('web.planet.works-list')}}" type="button" class="btn btn-primary btn-lg">Work</a>
@if(request()->get('sort'))
    <a href="{{route('web.person.list')}}" type="button" class="btn btn-outline-primary btn-lg">Basic</a>
@endif
@if(request()->get('sort') != 'last_year')
    <a href="{{route('web.person.list', ['sort' => 'last_year'])}}" type="button" class="btn btn-outline-primary btn-lg">Soon time</a>
@endif
@if(request()->get('sort') != 'desc_last_year')
    <a href="{{route('web.person.list', ['sort' => 'desc_last_year'])}}" type="button" class="btn btn-outline-primary btn-lg">Far time</a>
@endif
