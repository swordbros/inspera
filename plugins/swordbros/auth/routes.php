<?php

use Illuminate\Support\Facades\Route;

Route::group([
    // 'prefix' => 'auth',
    // 'as' => 'auth.',
    'namespace' => 'Swordbros\Auth\Controllers',
    'middleware' => 'web',
], function () {
    Route::get('/auth/login/{provider}', 'LoginController@login');
    Route::get('/auth/redirect/{provider}', 'LoginController@redirect');
    Route::get('/auth/register/{provider}', 'LoginController@register');
    Route::get('/auth/callback/{provider}', 'LoginController@callbackProvider');
});
