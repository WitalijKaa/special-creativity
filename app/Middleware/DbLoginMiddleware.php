<?php

namespace App\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DbLoginMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($model = Auth::user()) {
            define('DB', $model->login);
        }

        return $next($request);
    }
}
