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


// Route::post('login', 'API\UserController@login');


Route::group(['prefix' => 'chat'], function(){
	Route::get('/{id}', 'API\ChatController@index');
	Route::get('/show/{id}', 'API\ChatController@show');
	Route::post('/setVoluntary', 'API\ChatController@setVoluntary');
	Route::get('/searchVoluntary/{id}', 'API\ChatController@searchVoluntary');
});
Route::group(['prefix' => 'user'], function(){
	Route::get('/getUser/{id}', 'API\UserController@getUser');
});

Route::post('login', 'Auth\LoginController@login');
Route::post('register', 'Auth\RegisterController@register');

Route::group([
    'prefix' => 'restricted',
    'middleware' => 'auth:api',
], function () {
    // Authentication Routes...
    Route::get('logout', 'Auth\LoginController@logout');

    Route::get('/test', function () {
        return 'authenticated';
    });
});
