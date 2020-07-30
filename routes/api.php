<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('management')->group(function () {

    Route::prefix('admins')->group(function () {
        Route::get('actions', 'AdminActionsController@getActions');
    });
});
/*
Route::prefix('management')->group(function () {
    Route::get('users', '');
    Route::put('users', '');
    Route::delete('users', '');

    Route::prefix('admins')->group(function () {
        Route::get('actions', 'AdminActionsController@getActions');

        Route::get('roles', '');
        Route::post('roles', '');
        Route::put('roles', '');
        Route::delete('roles', '');

        Route::get('admins_information', '');
        Route::post('admins_information', '');
        Route::put('admins_information', '');
        Route::delete('admins_information', '');
    });

    Route::prefix('points_of_sale')->group(function () {
        Route::get('schedules', '');
        Route::post('schedules', '');
        Route::put('schedules', '');
        Route::delete('schedules', '');

        Route::get('points_of_sale', '');
        Route::post('points_of_sale', '');
        Route::put('points_of_sale', '');
        Route::delete('points_of_sale', '');
    });

    Route::prefix('products')->group(function () {
        Route::get('items', '');
        Route::post('items', '');
        Route::put('items', '');
        Route::delete('items', '');

        Route::get('products_information', '');
        Route::post('products_information', '');
        Route::put('products_information', '');
        Route::delete('products_information', '');

        Route::get('categories_item', '');
        Route::post('categories_item', '');
        Route::put('categories_item', '');
        Route::delete('categories_item', '');
    });
});
