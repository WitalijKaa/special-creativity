<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => [\Illuminate\Auth\Middleware\Authenticate::class, \App\Middleware\DbLoginMiddleware::class]], function() {

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('params', App\Http\Controllers\Planet\PlanerCreator\PlanetParamsAction::class)->name('params');
        Route::post('params/save', App\Http\Controllers\Planet\PlanerCreator\PlanetSaveAction::class)->name('save');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
