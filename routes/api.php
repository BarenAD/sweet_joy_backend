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

Route::prefix('authentication')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('authentication')->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('allLogout', 'AuthController@allLogout');
    });

    Route::prefix('management')->group(function () {

        Route::prefix('admins')->group(function () {
            Route::get('actions', 'AdminActionsController@getActions');

            Route::get('roles', 'AdminRolesController@getRoles');
            Route::post('roles', 'AdminRolesController@createRole');
            Route::put('roles', 'AdminRolesController@changeRole');
            Route::delete('roles', 'AdminRolesController@deleteRole');

            Route::get('admins', 'AdminInformationController@getAdmins');
            Route::post('admins', 'AdminInformationController@createAdmin');
//            Route::put('admins', '');
            Route::delete('admins', 'AdminInformationController@deleteAdmin');
        });

        Route::prefix('points_of_sale')->group(function () {
            Route::get('schedules', 'PointsOfSaleSchedulesController@getSchedules');
            Route::post('schedules', 'PointsOfSaleSchedulesController@createSchedule');
            Route::put('schedules', 'PointsOfSaleSchedulesController@changeSchedule');
            Route::delete('schedules', 'PointsOfSaleSchedulesController@deleteSchedules');

            Route::get('points_of_sale', 'PointsOfSaleController@getPoints');
            Route::post('points_of_sale', 'PointsOfSaleController@createPoints');
            Route::put('points_of_sale', 'PointsOfSaleController@changePoints');
            Route::delete('points_of_sale', 'PointsOfSaleController@deletePoints');
        });

        Route::prefix('products')->group(function () {
            Route::get('categories_item', 'ProductCategoriesItemController@getCategories');
            Route::post('categories_item', 'ProductCategoriesItemController@createCategory');
            Route::put('categories_item', 'ProductCategoriesItemController@changeCategory');
            Route::delete('categories_item', 'ProductCategoriesItemController@deleteCategory');

            Route::get('items', 'ProductItemsController@getItems');
            Route::post('items', 'ProductItemsController@createItem');
            Route::put('items', 'ProductItemsController@changeItem'); //поставить POST и будет всё ок
            Route::delete('items', 'ProductItemsController@deleteItem');

            Route::get('products_information', 'ProductInformationController@getProductsInfo');
            Route::post('products_information', 'ProductInformationController@createProductInfo');
            Route::put('products_information', 'ProductInformationController@changeProductInfo');
            Route::delete('products_information', 'ProductInformationController@deleteProductInfo');
        });
    });
});
/*
Route::prefix('management')->group(function () {
    Route::get('users', '');
    Route::put('users', '');
    Route::delete('users', '');
});
