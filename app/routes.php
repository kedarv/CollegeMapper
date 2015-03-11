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
Route::when('*', 'csrf', array('post', 'put', 'delete'));
Route::get('/', 'PageController@showHome');
Route::get('/makemark', 'PageController@makeMark');
Route::post('/postmark', 'PageController@postMark');