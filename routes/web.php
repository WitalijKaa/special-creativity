<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () { return view('login'); })->name('login');
Route::post('/web-auth', [\App\Http\Controllers\AuthController::class, 'webAuth'])->name('web-auth');

Route::group(['as' => 'web.', 'middleware' => ['web-auth']], function() {

    Route::get('escape', [\App\Http\Controllers\AuthController::class, 'escape'])->name('logout');

    Route::group(['as' => 'planet.', 'prefix' => 'planet'], function() {
        Route::get('once-create', App\Http\Controllers\Planet\PlanetCreator\PlanetOnceCreateAction::class)->name('once-create');
        Route::post('once-create/save', App\Http\Controllers\Planet\PlanetCreator\PlanetCreateAction::class)->name('save');

        Route::match(['get','post'], 'export', App\Http\Controllers\Planet\PlanetCreator\PlanetExportAction::class)->name('export');
        Route::match(['get','post'], 'import', App\Http\Controllers\Planet\PlanetCreator\PlanetImportAction::class)->name('import');

        Route::get('event/{id}', \App\Http\Controllers\Person\PersonEventFormAction::class)->whereNumber('id')->name('event-edit-form');
    });

    Route::group(['as' => 'person.', 'prefix' => 'life'], function() {
        Route::match(['get','post'], 'personas', \App\Http\Controllers\Person\PersonListAction::class)->name('list');
        Route::match(['get','post'], 'life-path/{id}', \App\Http\Controllers\Person\PersonDetailsAction::class)->whereNumber('id')->name('details');

        Route::get('master-poetry/{life_id}', \App\Http\Controllers\Person\Poetry\LifeMasterPoetryAction::class)->whereNumber('life_id')->name('master-poetry');
        Route::get('poetry/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryAction::class)->whereNumber('life_id')->name('poetry-life');
        Route::get('poetry-tech/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryViewTechAction::class)->whereNumber('life_id')->name('poetry-life-tech');
        Route::get('poetry/compare/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryCompareAction::class)->whereNumber('life_id')->name('poetry-life-compare-paragraphs');
        Route::get('poetry/compare-alpha/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryCompareAlphaAction::class)->whereNumber('life_id')->name('poetry-life-compare-paragraphs-alpha');
        Route::get('poetry/compare-tech/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryCompareTechAction::class)->whereNumber('life_id')->name('poetry-life-compare-paragraphs-tech');

        Route::get('poetry-edit/{life_id}/{lang}/{llm}', \App\Http\Controllers\Person\Poetry\ParagraphsEditAction::class)->whereNumber('life_id')->name('poetry-life-edit');
        Route::post('poetry-improve/{life_id}', \App\Http\Controllers\Person\Poetry\ChapterImproveAction::class)->whereNumber('life_id')->name('poetry-life-improve');
        Route::post('poetry-finale/{life_id}', \App\Http\Controllers\Person\Poetry\ChapterFinalAction::class)->whereNumber('life_id')->name('poetry-life-finale');
        Route::post('poetry-master/{life_id}/{lang}/{llm}', \App\Http\Controllers\Person\Poetry\LifePoetryMasterAction::class)->whereNumber('life_id')->name('poetry-life-master');
        Route::post('poetry/translate-simple/{life_id}', \App\Http\Controllers\Person\Poetry\LifePoetryTranslateAction::class)->whereNumber('life_id')->name('poetry-life-translate');
        Route::post('poetry/versions/{life_id}/{specific?}', \App\Http\Controllers\Person\Poetry\LifePoetryVersionsAction::class)->whereNumber('life_id')->name('poetry-life-versions');
        Route::post('poetry/translate-simple-again/{life_id}/{specific?}', \App\Http\Controllers\Person\Poetry\LifePoetryTranslateAgainAction::class)->whereNumber('life_id')->name('poetry-life-translate-again');
        Route::post('poetry-delete/{life_id}/{lang}/{llm}', \App\Http\Controllers\Person\Poetry\LifePoetryDeleteAction::class)->whereNumber('life_id')->name('poetry-life-delete');
        Route::post('poetry-paragraph-change/{id}', \App\Http\Controllers\Person\Poetry\ParagraphChangeAction::class)->whereNumber('id')->name('poetry-paragraph-change');
        Route::post('poetry-paragraph-delete/{id}', \App\Http\Controllers\Person\Poetry\ParagraphDeleteAction::class)->whereNumber('id')->name('poetry-paragraph-delete');
        Route::post('poetry-paragraph-move-down/{id}', \App\Http\Controllers\Person\Poetry\ParagraphMoveDownAction::class)->whereNumber('id')->name('poetry-paragraph-move-down');
        Route::post('poetry/add/{life_id}', \App\Http\Controllers\Person\Poetry\ChapterAddAction::class)->whereNumber('life_id')->name('chapter-add');
        Route::post('poetry/translate/{life_id}', \App\Http\Controllers\Person\Poetry\ChapterTranslateAction::class)->whereNumber('life_id')->name('chapter-translate');
        Route::get('poetry-words', \App\Http\Controllers\Person\Poetry\PoetryWordsAction::class)->name('poetry-words');
        Route::get('poetry-word-edit/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordEditAction::class)->whereNumber('id')->name('poetry-word-edit');
        Route::post('poetry-word-add', \App\Http\Controllers\Person\Poetry\PoetryWordAddAction::class)->name('poetry-word-add');
        Route::post('poetry-word-change/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordChangeAction::class)->whereNumber('id')->name('poetry-word-change');
        Route::post('poetry-word-delete/{id}', \App\Http\Controllers\Person\Poetry\PoetryWordDeleteAction::class)->whereNumber('id')->name('poetry-word-delete');

        Route::get('{person_id}/{life_id}', \App\Http\Controllers\Person\LifeDetailsAction::class)->whereNumber(['person_id', 'life_id'])->name('details-life');
        Route::post('add-person/{author_id}', \App\Http\Controllers\Person\PersonAddAction::class)->whereNumber('author_id')->name('add');
        Route::post('add-life/{id}', \App\Http\Controllers\Person\LifeAddAction::class)->whereNumber('id')->name('add-life');
        Route::post('add-life-event/{id}', \App\Http\Controllers\Person\PersonEventAction::class)->whereNumber('id')->name('add-event');
    });

    Route::group(['as' => 'basic.', 'prefix' => 'basic'], function() {
        Route::get('space', App\Http\Controllers\BasicAction::class)->name('space');
        Route::match(['get','post'], 'events', \App\Http\Controllers\Person\EventListAction::class)->name('events');
        Route::post('event', \App\Http\Controllers\Planet\EventTypeAddAction::class)->name('event-type');
        Route::post('event-edit/{id}', \App\Http\Controllers\Person\PersonEventEditAction::class)->whereNumber('id')->name('event-edit');

        Route::match(['get','post'], 'work/{id}', \App\Http\Controllers\Planet\Work\WorksDetailsAction::class)->whereNumber('id')->name('works-details');
        Route::match(['get','post'], 'works-list', \App\Http\Controllers\Planet\Work\WorksListAction::class)->name('works-list');
        Route::get('work-create', App\Http\Controllers\Planet\Work\WorkCreateAction::class)->name('work-create');
        Route::post('work-add', \App\Http\Controllers\Planet\Work\WorkAddAction::class)->name('work-add');
        Route::post('work-edit/{id}', \App\Http\Controllers\Planet\Work\WorkEditAction::class)->whereNumber('id')->name('work-edit');
        Route::get('work-correct/{id}', \App\Http\Controllers\Planet\Work\WorkCorrectAction::class)->whereNumber('id')->name('work-correct');
    });

    Route::group(['as' => 'visual.', 'prefix' => 'routine'], function() {
        Route::match(['get','post'], 'lives-timeline', \App\Http\Controllers\Person\Visual\LivesTimelineAction::class)->name('lives-timeline');
        Route::match(['get','post'], 'years-population', \App\Http\Controllers\Person\Visual\YearsPopulationAction::class)->name('years-population');
    });

    Route::group(['as' => 'routine.', 'prefix' => 'routine'], function() {
        Route::get('life-work-army/{id}', \App\Http\Controllers\Person\Routine\WorkSlaveAction::class)->whereNumber('id')->name('life-work-army');
        Route::get('create-persons', \App\Http\Controllers\Person\Routine\CreatePersonsAction::class)->name('create-persons');
        Route::get('allods-live-cycle', \App\Http\Controllers\Person\Routine\CycleLifeAtAllodsAction::class)->name('allods-live-cycle');
        Route::get('planet-live-cycle', \App\Http\Controllers\Person\Routine\CycleLifeAtPlanetAction::class)->name('planet-live-cycle');

        Route::get('check/force-vs-creation', \App\Http\Controllers\Person\Routine\CheckForceVsRoutineAction::class)->name('check.force-vs-creation');
        Route::get('re-write/force-vs-creation', \App\Http\Controllers\Person\Routine\ReWriteForceVsRoutineAction::class)->name('re-write.force-vs-creation');
    });

    Route::group(['as' => 'prediction.', 'prefix' => 'prediction'], function() {
        Route::match(['get','post'], 'future-simple', \App\Http\Controllers\Prediction\FuturePredictionAction::class)->name('future');
    });
});

Route::get('/dd', [\App\Http\Controllers\DevController::class, 'ddDev']);
