<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentLocationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\SiteConfigurationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
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
        Route::post('allLogout', 'AuthController@allLogoutb');
    });

    Route::middleware(['checkAllowToManagement'])->prefix('management')->as('management.')->group(function () {

        Route::apiResource('users', UserController::class)->except('store');
        Route::apiResource('categories', CategoryController::class);

        Route::apiResource('products', ProductController::class)->except('update');
        Route::post('products/{id}', [ProductController::class, 'update'])->name('products.update');

        Route::apiResource('shops', ShopController::class);
        Route::apiResource('shops-products', ShopProductController::class);
//        Route::prefix('shops/{shop_id}')->as('shops.')->group(function () { //Планы на будущее
//            Route::apiResource('products', ShopAssortmentController::class);
//            ...передача товаров массивами
//        });
        Route::apiResource('schedules', ScheduleController::class);

        Route::prefix('configurations')->as('configurations.')->group(function () {
            Route::apiResource('site', SiteConfigurationController::class)->only(['index', 'show', 'update']);
        });

        Route::prefix('documents')->as('documents.')->group(function () {
            Route::apiResource('locations', DocumentLocationController::class)->only(['index', 'show', 'update']);
        });
        Route::apiResource('/documents', DocumentController::class);

        //#######################################

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
    });
});
