<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => [\Illuminate\Auth\Middleware\Authenticate::class, \App\Middleware\DbLoginMiddleware::class]], function() {

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('params', App\Http\Controllers\Planet\PlanetCreator\PlanetParamsAction::class)->name('params');
        Route::get('export', App\Http\Controllers\Planet\PlanetCreator\PlanetExportAction::class)->name('export');
        Route::post('params/save', App\Http\Controllers\Planet\PlanetCreator\PlanetSaveAction::class)->name('save');
    });

    Route::group(['as' => 'person.', 'prefix' => 'life'], function() {
        Route::any('personas', \App\Http\Controllers\Person\PersonListAction::class)->name('list');
        Route::any('life-path/{id}', \App\Http\Controllers\Person\PersonDetailsAction::class)->where('id', '[0-9]+')->name('details');
        Route::get('{person_id}/{life_id}', \App\Http\Controllers\Person\LifeDetailsAction::class)->where(['person_id', 'life_id'], '[0-9]+')->name('details-life');
        Route::get('create', \App\Http\Controllers\Person\PersonFormAction::class)->name('form');
        Route::post('add-person/{author_id}', \App\Http\Controllers\Person\PersonAddAction::class)->where('author_id', '[0-9]+')->name('add');
        Route::post('add-life/{id}', \App\Http\Controllers\Person\PersonLifeAction::class)->where('id', '[0-9]+')->name('add-life');
        Route::post('add-life-event/{id}', \App\Http\Controllers\Person\PersonEventAction::class)->where('id', '[0-9]+')->name('add-event');
    });

    Route::group(['as' => 'basic.', 'prefix' => 'basic'], function() {
        Route::post('event', App\Http\Controllers\Planet\PlanetCreator\EventTypeAddAction::class)->name('event-type');
        Route::post('work', App\Http\Controllers\Planet\PlanetCreator\WorkAddAction::class)->name('work');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
