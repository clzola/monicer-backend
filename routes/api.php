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

Route::group(['middleware' => 'api', 'namespace' => 'Api'], function ($router) {
    Route::post('auth/token', 'Auth\AuthController@login');
    Route::delete('auth/token', 'Auth\AuthController@logout');
    Route::post('auth/refresh', 'Auth\AuthController@refresh');
    Route::get('me', 'Auth\AuthController@me');

    Route::get('/wallet', 'WalletsController@getMyWallet');
    Route::get('/transactions', 'TransactionsController@transactions');

    Route::post('/shop/initiate-payment', 'PaymentController@pay');

    Route::post('/rooms/create', 'RoomsController@create');
    Route::post('/rooms/{code}/join', 'RoomsController@join');
    Route::post('/rooms/{code}/kick/{user}', 'RoomsController@kickUser');
    Route::post('/rooms/{code}/recycle', 'RoomsController@recycle');

    Route::post('/rooms/validate-split', 'PaymentController@validateSplit');
    Route::post('/rooms/{code}/rebalance', 'PaymentController@rebalance');
});

Route::get('/shops', 'Api\ShopsController@getShops');
Route::get('/shops/{shop}', 'Api\ShopsController@shop');

Route::get('/test/broadcast', 'Test\BroadcastController@test');
