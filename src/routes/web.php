<?php
/**
 * This file is part of the Laravel Interswitch package
 * (c) Interswitch Group | 2021
 */
    
Route::group(['namespace' => 'Interswitch\Interswitch\Http\Controllers'], function () {
    Route::post('/interswitch-pay', 'InterswitchController@pay');

    Route::post('/interswitch-callback', 'InterswitchController@callback');
});