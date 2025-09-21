<form action="{{$route}}" method="{{empty($method) ? 'post' : $method}}" style="display: inline-block;">
    @csrf
    <button type="submit" class="btn btn-danger btn-sm">{{$btn}}</button>
</form>
