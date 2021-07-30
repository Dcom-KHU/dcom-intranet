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

Route::get('/user/id/{keyword}', 'Auth\RegisterController@check_id');
Route::get('/user/email/{keyword}', 'Auth\RegisterController@check_email');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/jokbo/subject/{keyword}', 'BoardController@jokbo_subject');
    Route::get('/jokbo/professor/{keyword}', 'BoardController@jokbo_professor');
    Route::get('/jokbo/title/{keyword}', 'BoardController@jokbo_title');
});