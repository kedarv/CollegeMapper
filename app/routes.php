<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/
Route::get('/', 'PageController@showHome');
Route::get('/makemark', 'PageController@makeMark');
Route::get('/stats', 'PageController@stats');
Route::post('/postmark', 'PageController@postMark', array('before' => 'csrf'));
Route::post('/processInfo', 'PageController@processInfo', array('before' => 'csrf-ajax'));