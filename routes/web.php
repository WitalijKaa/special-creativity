<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => [\Illuminate\Auth\Middleware\Authenticate::class, \App\Middleware\DbLoginMiddleware::class]], function() {

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('params', App\Http\Controllers\Planet\PlanetCreator\PlanetParamsAction::class)->name('params');
        Route::post('params/save', App\Http\Controllers\Planet\PlanetCreator\PlanetSaveAction::class)->name('save');
    });

    Route::group(['as' => 'person.', 'prefix' => 'life'], function() {
        Route::get('personas', \App\Http\Controllers\Person\PersonListAction::class)->name('list');
        Route::get('life-path/{id}', \App\Http\Controllers\Person\PersonDetailsAction::class)->where('id', '[0-9]')->name('details');
        Route::get('create', \App\Http\Controllers\Person\PersonFormAction::class)->name('form');
        Route::post('add-person', \App\Http\Controllers\Person\PersonAddAction::class)->name('add');
        Route::post('add-life/{id}', \App\Http\Controllers\Person\PersonLifeAction::class)->where('id', '[0-9]')->name('add-life');
    });

    Route::group(['as' => 'basic.', 'prefix' => 'basic'], function() {
        Route::post('life-type', \App\Http\Controllers\Planet\LifeCreator\LifeTypeAddAction::class)->name('life-type');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
