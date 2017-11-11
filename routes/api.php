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
	Route::post('/sendMessage', 'API\ChatController@sendMessage');
	Route::get('/searchVoluntary/{id}', 'API\ChatController@searchVoluntary');
});
Route::group(['prefix' => 'user'], function(){
	Route::get('/getUser/{id}', 'API\UserController@getUser');
});

Route::group(['prefix' => 'post'], function(){
	Route::get('/index/{filter}/{user}', 'API\PostController@index');
	Route::get('/show/{id}', 'API\PostController@show');
	Route::post('/create', 'API\PostController@create');
	Route::get('/likePost/{id}/{user}', 'API\PostController@likePost');
	Route::get('/favoritePost/{id}/{user}', 'API\PostController@favoritePost');
	Route::post('/getParsedDate', 'API\PostController@getParsedDate');
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
