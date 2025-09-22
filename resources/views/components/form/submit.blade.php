<div class="container mb-4">
    <div class="row justify-content-center">
        <div class="col-12 col-md-10">
            <form action="{{$route}}" method="{{empty($method) ? 'post' : $method}}">
                @csrf
                <button type="submit" class="btn {{empty($color) ? 'btn-primary' : 'btn-' . $color}} btn-lg">{{$btn}}</button>
            </form>
        </div>
    </div>
</div>
