<?php

use App\Http\Controllers\DocumentsController;
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

Route::get('products_for_users', 'ProductInformationController@getProductsForUsers');

Route::prefix('authentication')->group(function () {
    Route::post('register', 'AuthController@register');
    Route::post('login', 'AuthController@login');
});

Route::middleware(['auth:sanctum'])->namespace('\\')->group(function () {
    Route::prefix('authentication')->group(function () {
        Route::post('logout', 'AuthController@logout');
        Route::post('allLogout', 'AuthController@allLogout');
    });

    Route::middleware(['checkAllowToManagement'])->prefix('management')->group(function () {
        Route::get('users/{id?}', 'UsersController@getUsers');
        Route::post('users/{id}', 'UsersController@changeUser');
        Route::delete('users/{id}', 'UsersController@deleteUser');

        Route::prefix('configurations')->group(function () {
            Route::get('site/{id?}', 'SiteConfigurationsController@getSiteConfigurations');
            Route::post('site/{id}', 'SiteConfigurationsController@changeSiteConfigurations');
        });

        Route::prefix('admins')->group(function () {
            Route::get('actions', 'AdminActionsController@getActions');

            Route::get('roles/{id?}', 'AdminRolesController@getRoles');
            Route::post('roles', 'AdminRolesController@createRole');
            Route::post('roles/{id}', 'AdminRolesController@changeRole');
            Route::delete('roles/{id}', 'AdminRolesController@deleteRole');

            Route::get('admins/{id_user?}', 'AdminInformationController@getAdmins');
            Route::post('admins', 'AdminInformationController@createAdmin');
            Route::post('admins/{id_user}', 'AdminInformationController@changeAdmin');
            Route::delete('admins/{id_user}', 'AdminInformationController@deleteAdmin');
        });

        Route::prefix('points_of_sale')->group(function () {
            Route::get('schedules/{id?}', 'SchedulesController@getSchedules');
            Route::post('schedules', 'SchedulesController@createSchedule');
            Route::post('schedules/{id}', 'SchedulesController@changeSchedule');
            Route::delete('schedules/{id}', 'SchedulesController@deleteSchedules');

            Route::get('points_of_sale/{id?}', 'PointsOfSaleController@getPoints');
            Route::post('points_of_sale', 'PointsOfSaleController@createPoints');
            Route::post('points_of_sale/{id}', 'PointsOfSaleController@changePoints');
            Route::delete('points_of_sale/{id}', 'PointsOfSaleController@deletePoints');
        });

        Route::prefix('products')->group(function () {
            Route::get('categories_item/{id?}', 'CategoriesItemController@getCategories');
            Route::post('categories_item', 'CategoriesItemController@createCategory');
            Route::post('categories_item/{id}', 'CategoriesItemController@changeCategory');
            Route::delete('categories_item/{id}', 'CategoriesItemController@deleteCategory');

            Route::get('items/{id?}', 'ItemsController@getItems');
            Route::post('items', 'ItemsController@createItem');
            Route::post('items/{id}', 'ItemsController@changeItem'); //поставить POST и будет всё ок
            Route::delete('items/{id}', 'ItemsController@deleteItem');

            Route::get('products_information/{id?}', 'ProductInformationController@getProductsInfo');
            Route::post('products_information', 'ProductInformationController@createProductInfo');
            Route::post('products_information/{id}', 'ProductInformationController@changeProductInfo');
            Route::delete('products_information/{id}', 'ProductInformationController@deleteProductInfo');
        });

        Route::prefix('documents')->group(function () {
            Route::get('locations/{id?}', 'LocationsDocumentsController@getLocationsDocuments');
            Route::post('locations/{id}', 'LocationsDocumentsController@changeLocationsDocuments');

            Route::apiResource('documents', DocumentsController::class);
        });
    });
});
