<a href="{{route('web.planet.params')}}" type="button" class="btn btn-secondary btn-lg">Planet</a>
<a href="{{route('web.planet.works-list')}}" type="button" class="btn btn-primary btn-lg">Work</a>
@if(request()->get('sort') > 0)
    <a href="{{route('web.person.list')}}" type="button" class="btn btn-outline-primary btn-lg">Basic</a>
@else
    <a href="{{route('web.person.list', ['sort' => 'desc_last_year'])}}" type="button" class="btn btn-outline-primary btn-lg">Last Year</a>
@endif
