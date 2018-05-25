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

Route::middleware('api')->post('/index', 'Api\IndexController@index');
Route::middleware('api')->post('/login', 'Api\AuthenticateController@login');

Route::middleware('api')->post('/user/create', 'Api\AuthenticateController@weappLogin');
Route::middleware('api')->post('/user/logout', 'Api\AuthenticateController@logout');
