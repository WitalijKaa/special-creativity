<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => [\Illuminate\Auth\Middleware\Authenticate::class, \App\Middleware\DbLoginMiddleware::class]], function() {

    Route::get('escape', [\App\Http\Controllers\AuthController::class, 'escape'])->name('logout');

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('once-create', App\Http\Controllers\Planet\PlanetCreator\PlanetOnceCreateAction::class)->name('once-create');
        Route::post('once-create/save', App\Http\Controllers\Planet\PlanetCreator\PlanetCreateAction::class)->name('save');

        Route::match(['get','post'], 'export', App\Http\Controllers\Planet\PlanetCreator\PlanetExportAction::class)->name('export');
        Route::match(['get','post'], 'import', App\Http\Controllers\Planet\PlanetCreator\PlanetImportAction::class)->name('import');

        Route::get('event/{id}', \App\Http\Controllers\Person\PersonEventFormAction::class)->where('id', '[0-9]+')->name('event-edit-form');
        Route::match(['get','post'], 'work/{id}', \App\Http\Controllers\Planet\Work\WorksDetailsAction::class)->where('id', '[0-9]+')->name('works-details');
        Route::match(['get','post'], 'work', \App\Http\Controllers\Planet\Work\WorksListAction::class)->name('works-list');
    });

    Route::group(['as' => 'person.', 'prefix' => 'life'], function() {
        Route::match(['get','post'], 'personas', \App\Http\Controllers\Person\PersonListAction::class)->name('list');
        Route::match(['get','post'], 'life-path/{id}', \App\Http\Controllers\Person\PersonDetailsAction::class)->where('id', '[0-9]+')->name('details');

        Route::get('poetry/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryAction::class)->where(['life_id'], '[0-9]+')->name('poetry-life');
        Route::get('poetry-edit/{life_id}/{lang}/{llm}', \App\Http\Controllers\Person\Poetry\ParagraphsEditAction::class)->where(['life_id'], '[0-9]+')->name('poetry-life-edit');
        Route::get('poetry-delete/{life_id}/{lang}/{llm}', \App\Http\Controllers\Person\Poetry\ParagraphsDeleteAction::class)->where(['life_id'], '[0-9]+')->name('poetry-life-delete');
        Route::post('poetry-paragraph-change/{id}', \App\Http\Controllers\Person\Poetry\ParagraphChangeAction::class)->where('id', '[0-9]+')->name('poetry-paragraph-change');
        Route::post('poetry-paragraph-delete/{id}', \App\Http\Controllers\Person\Poetry\ParagraphDeleteAction::class)->where('id', '[0-9]+')->name('poetry-paragraph-delete');
        Route::post('poetry-paragraph-move-down/{id}', \App\Http\Controllers\Person\Poetry\ParagraphMoveDownAction::class)->where('id', '[0-9]+')->name('poetry-paragraph-move-down');
        Route::post('poetry/add/{life_id}', \App\Http\Controllers\Person\Poetry\ChapterAddAction::class)->where(['life_id'], '[0-9]+')->name('chapter-add');
        Route::post('poetry/translate/{life_id}', \App\Http\Controllers\Person\Poetry\ChapterTranslateAction::class)->where(['life_id'], '[0-9]+')->name('chapter-translate');
        Route::get('poetry-words', \App\Http\Controllers\Person\Poetry\PoetryWordsAction::class)->name('poetry-words');
        Route::get('poetry-word-edit/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordEditAction::class)->where('id', '[0-9]+')->name('poetry-word-edit');
        Route::post('poetry-word-add', \App\Http\Controllers\Person\Poetry\PoetryWordAddAction::class)->name('poetry-word-add');
        Route::post('poetry-word-change/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordChangeAction::class)->where('id', '[0-9]+')->name('poetry-word-change');
        Route::post('poetry-word-translate/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordTranslateAction::class)->where('id', '[0-9]+')->name('poetry-word-translate');
        Route::post('poetry-word-delete/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordDeleteAction::class)->where('id', '[0-9]+')->name('poetry-word-delete');

        Route::get('{person_id}/{life_id}', \App\Http\Controllers\Person\LifeDetailsAction::class)->where(['person_id', 'life_id'], '[0-9]+')->name('details-life');
        Route::post('add-person/{author_id}', \App\Http\Controllers\Person\PersonAddAction::class)->where('author_id', '[0-9]+')->name('add');
        Route::post('add-life/{id}', \App\Http\Controllers\Person\LifeAddAction::class)->where('id', '[0-9]+')->name('add-life');
        Route::post('add-life-event/{id}', \App\Http\Controllers\Person\PersonEventAction::class)->where('id', '[0-9]+')->name('add-event');
    });

    Route::group(['as' => 'basic.', 'prefix' => 'basic'], function() {
        Route::get('space', App\Http\Controllers\BasicAction::class)->name('space');
        Route::match(['get','post'], 'events', \App\Http\Controllers\Person\EventListAction::class)->name('events');
        Route::post('event', \App\Http\Controllers\Planet\EventTypeAddAction::class)->name('event-type');
        Route::post('work', \App\Http\Controllers\Planet\Work\WorkAddAction::class)->name('work');
        Route::post('work-edit/{id}', \App\Http\Controllers\Planet\Work\WorkEditAction::class)->where('id', '[0-9]+')->name('work-edit');
        Route::get('work-correct/{id}', \App\Http\Controllers\Planet\Work\WorkCorrectAction::class)->where('id', '[0-9]+')->name('work-correct');
        Route::post('event-edit/{id}', \App\Http\Controllers\Person\PersonEventEditAction::class)->where('id', '[0-9]+')->name('event-edit');
    });

    Route::group(['as' => 'visual.', 'prefix' => 'routine'], function() {
        Route::match(['get','post'], 'lives-timeline', \App\Http\Controllers\Person\Visual\LivesTimelineAction::class)->name('lives-timeline');
        Route::match(['get','post'], 'years-population', \App\Http\Controllers\Person\Visual\YearsPopulationAction::class)->name('years-population');
    });

    Route::group(['as' => 'routine.', 'prefix' => 'routine'], function() {
        Route::get('life-work-army/{id}', \App\Http\Controllers\Person\Routine\WorkSlaveAction::class)->where('id', '[0-9]+')->name('life-work-army');
        Route::get('create-persons', \App\Http\Controllers\Person\Routine\CreatePersonsAction::class)->name('create-persons');
        Route::get('allods-live-cycle', \App\Http\Controllers\Person\Routine\CycleLifeAtAllodsAction::class)->name('allods-live-cycle');
        Route::get('planet-live-cycle', \App\Http\Controllers\Person\Routine\CycleLifeAtPlanetAction::class)->name('planet-live-cycle');
    });

    Route::group(['as' => 'prediction.', 'prefix' => 'prediction'], function() {
        Route::match(['get','post'], 'future-simple', \App\Http\Controllers\Prediction\FuturePredictionAction::class)->name('future');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
