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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/', function () {
    return response()->json(['message' => 'It\'s Works', 'status' => 'Connected']);;
});

Route::resource('register', 'Auth\RegisterController');
Route::resource('login', 'Auth\LoginController');

Route::resource('films', 'FilmsController');
Route::resource('films', 'FilmsController');
Route::resource('comments', 'CommentsController');
Route::resource('genres', 'GenresController');