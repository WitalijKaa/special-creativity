<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Creativity</title>

        @vite(['resources/styles/app.scss', 'resources/js/app.js'])
    </head>
    <body>
        <header class="container text-center">
            <h1>Main page</h1>
            <hr class="border border-danger border-2">
        </header>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-10 col-md-8 col-lg-6">
                    <form action="{{route('web-auth')}}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="login" class="form-label">Login</label>
                            <input id="login" name="login" value="{{old('login')}}" type="text" class="form-control" aria-describedby="loginTip">
                            @if(empty($errors->get('login')[0]))
                                <div id="loginTip" class="form-text">U will be registered if u are new.</div>
                            @else
                                <div id="loginTip" class="form-text text-danger">{{ $errors->get('login')[0] }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="pass" class="form-label">Password</label>
                            <input id="pass" name="pass" type="password" class="form-control" aria-describedby="passTip">
                            @if(!empty($errors->get('pass')[0]))
                                <div id="passTip" class="form-text text-danger">{{ $errors->get('pass')[0] }}</div>
                            @endif
                        </div>
                        <div class="d-grid offset-md-8 col-md-4">
                            <button type="submit" class="btn btn-outline-success">Allod-Ulaz</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </body>
</html>
