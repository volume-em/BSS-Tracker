<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'investigators'], function () {
    Route::get('/', [\App\Http\Controllers\Api\InvestigatorsController::class, 'index']);
    Route::get('/search', [\App\Http\Controllers\Api\InvestigatorsController::class, 'search']);
    Route::post('/', [\App\Http\Controllers\Api\InvestigatorsController::class, 'store']);
    Route::get('/{investigator}', [\App\Http\Controllers\Api\InvestigatorsController::class, 'show']);
    Route::put('/{investigator}', [\App\Http\Controllers\Api\InvestigatorsController::class, 'update']);
});

Route::group(['prefix' => 'projects'], function () {
    Route::get('/', [\App\Http\Controllers\Api\ProjectsController::class, 'index']);
    Route::get('/search', [\App\Http\Controllers\Api\ProjectsController::class, 'search']);
    Route::post('/', [\App\Http\Controllers\Api\ProjectsController::class, 'store']);
    Route::get('/{project}', [\App\Http\Controllers\Api\ProjectsController::class, 'show']);
    Route::put('/{project}', [\App\Http\Controllers\Api\ProjectsController::class, 'update']);
});

Route::group(['prefix' => 'bio-samples'], function () {

});

Route::group(['prefix' => 'samples'], function () {

});

Route::group(['prefix' => 'specimens'], function () {

});
