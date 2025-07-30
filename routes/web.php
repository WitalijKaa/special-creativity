<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => [\Illuminate\Auth\Middleware\Authenticate::class, \App\Middleware\DbLoginMiddleware::class]], function() {

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('params', App\Http\Controllers\Planet\PlanetCreator\PlanetParamsAction::class)->name('params');
        Route::get('export', App\Http\Controllers\Planet\PlanetCreator\PlanetExportAction::class)->name('export');
        Route::post('params/save', App\Http\Controllers\Planet\PlanetCreator\PlanetSaveAction::class)->name('save');

        Route::get('event/{id}', \App\Http\Controllers\Person\PersonEventFormAction::class)->where('id', '[0-9]+')->name('event-edit-form');
        Route::any('work/{id}', \App\Http\Controllers\Planet\Work\WorksDetailsAction::class)->where('id', '[0-9]+')->name('works-details');
        Route::any('work', \App\Http\Controllers\Planet\Work\WorksListAction::class)->name('works-list');
    });

    Route::group(['as' => 'person.', 'prefix' => 'life'], function() {
        Route::any('personas', \App\Http\Controllers\Person\PersonListAction::class)->name('list');
        Route::any('life-path/{id}', \App\Http\Controllers\Person\PersonDetailsAction::class)->where('id', '[0-9]+')->name('details');
        Route::get('{person_id}/{life_id}', \App\Http\Controllers\Person\LifeDetailsAction::class)->where(['person_id', 'life_id'], '[0-9]+')->name('details-life');
        Route::post('add-person/{author_id}', \App\Http\Controllers\Person\PersonAddAction::class)->where('author_id', '[0-9]+')->name('add');
        Route::post('add-life/{id}', \App\Http\Controllers\Person\LifeAddAction::class)->where('id', '[0-9]+')->name('add-life');
        Route::post('add-life-event/{id}', \App\Http\Controllers\Person\PersonEventAction::class)->where('id', '[0-9]+')->name('add-event');
    });

    Route::group(['as' => 'basic.', 'prefix' => 'basic'], function() {
        Route::post('event', \App\Http\Controllers\Planet\EventTypeAddAction::class)->name('event-type');
        Route::post('work', \App\Http\Controllers\Planet\Work\WorkAddAction::class)->name('work');
        Route::post('work-edit/{id}', \App\Http\Controllers\Planet\Work\WorkEditAction::class)->where('id', '[0-9]+')->name('work-edit');
        Route::get('work-correct/{id}', \App\Http\Controllers\Planet\Work\WorkCorrectAction::class)->where('id', '[0-9]+')->name('work-correct');
        Route::post('event-edit/{id}', \App\Http\Controllers\Person\PersonEventEditAction::class)->where('id', '[0-9]+')->name('event-edit');
    });

    Route::group(['as' => 'prediction.', 'prefix' => 'prediction'], function() {
        Route::any('future-simple', \App\Http\Controllers\Prediction\FuturePredictionAction::class)->name('future');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
