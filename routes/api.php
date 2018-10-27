<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(array('prefix' => 'auth'), function(){
    Route::post('register', ['as' => 'auth.register', 'uses' => 'Auth\RegisterController@create']);
    Route::post('login', ['as' => 'auth.login', 'uses' => 'Auth\LoginController@login']);
});

Route::group(array('prefix' => 'films'), function(){
    Route::post('create', ['as' => 'films.create', 'uses' => 'FilmsController@create']);
    Route::get('{pag}', ['as' => 'films.index', 'uses' => 'FilmsController@index'])->where('pag', '[0-9]+');
    Route::get('{slug}', ['as' => 'films.search', 'uses' => 'FilmsController@search']);
});

Route::group(array('prefix' => 'comments'), function(){
    Route::get('create', ['as' => 'comments.create', 'uses' => 'CommentsController@create']);
    Route::get('film/{id}', ['as' => 'comments.search.film', 'uses' => 'CommentsController@searchByFilm'])->where('id', '[0-9]+');
});