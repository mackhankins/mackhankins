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

Route::get('/', ['as' => '/', 'uses' => 'Pub\IndexController@index']);
Route::get('/blog', ['as' => 'blog', 'uses' => 'Pub\BlogController@index']);
Route::get('/blog/{slug}', 'Pub\BlogController@single');
