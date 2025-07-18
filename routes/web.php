<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('main'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => [\Illuminate\Auth\Middleware\Authenticate::class]], function() {

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('params', App\Http\Controllers\Planet\PlanerCreator\PlanetParamsAction::class)->name('params');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
