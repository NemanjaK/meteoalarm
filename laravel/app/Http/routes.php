<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/api/subscribe', 'SubscribeController',
    ['only' => ['store', 'destroy']]);

Route::resource('/api/notify', 'NotifyController',
    ['only' => ['show']]);

Route::resource('/api/quality', 'QualityController',
    ['only' => ['index']]);

Route::resource('/api/station', 'StationController',
    ['only' => ['index', 'show']]);

Route::resource('/api/component', 'ComponentController',
    ['only' => ['index', 'show']]);

Route::resource('/api/measurement', 'MeasurementController',
    ['only' => ['index', 'show']]);