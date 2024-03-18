<?php

use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'oauth', 'middleware' => [\App\Http\Middleware\OauthIsEnabled::class]], function () {
    Route::get('/redirect', [\App\Http\Controllers\OAuthController::class, 'redirect'])->name('oauth.redirect');
    Route::get('/callback', [\App\Http\Controllers\OAuthController::class, 'callback'])->name('oauth.callback');
});

Route::group(['prefix' => 'setup', 'middleware' => [\App\Http\Middleware\RedirectIfApplicationIsSetup::class]], function () {
    Route::get('/', \App\Livewire\SetupApplication::class);
});

Route::get('/readme', fn() => view('readme'));
Route::get('/reset-password/{passwordResetToken:token}', \App\Livewire\ResetPassword::class);
