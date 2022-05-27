<?php

use Illuminate\Support\Facades\Route;

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
Route::namespace('App\Http\Controllers\Api')->group(function () {
    Route::post('register', [ 'uses' => 'AuthController@postRegister']);
    Route::post('login', [ 'uses' => 'AuthController@postLogin']);

    Route::group(['middleware' => [ 'jwt.verify']], function(){
        Route::post('page/create', 'PageController@storePage');
        Route::post('person/attach-post', 'PostController@attachPost');
        Route::post('page/{pageId}/attach-post', 'PostController@attachPostbyPage');

        Route::post('follow/person/{personId}', 'UserController@followPerson');
        Route::post('follow/page/{pageId}', 'UserController@followPage');
        Route::post('person/feed', 'UserController@feed');
    });
});
