<?php

namespace App\Http\Controllers;

use App\Models\User;
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
        return redirect(route('web.planet.params'));
    }
}
