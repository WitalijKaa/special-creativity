<?php

namespace App\Http\Controllers;

use App\Middleware\DbLoginMiddleware;
use App\Migrations\Migrator;
use App\Models\User;
use App\Models\World\Planet;
use App\Requests\WebAuthRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function webAuth(WebAuthRequest $request)
    {
        $model = User::whereLogin($request->login)->first();

        if (!$model && User::count() < 101) {
            $model = new User();
            $model->login = $request->login;
            $model->password = $request->pass;
            $model->save();
        }

        if (!password_verify($request->pass, $model->password))
        {
            return redirect(route('login'))
                ->withErrors(['pass' => ['Correct pass required']])
                ->withInput(['login' => $request->login]);
        }

        Auth::guard('web')->login($model);
        (new DbLoginMiddleware())->handle($request, fn () => true);
        Migrator::migrate();
        if (Planet::count()) {
            return redirect(route('web.space.basic'));
        }
        return redirect(route('web.planet.params'));
    }

    public function escape()
    {
        Auth::guard('web')->logout();
        return redirect(route('login'));
    }
}
