<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentLocationController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ShopProductController;
use App\Http\Controllers\SiteConfigurationController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
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

Route::get('data', [MasterDataController::class, 'masterData'])->name('master.data');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware(['auth:sanctum'])->namespace('\\')->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('allLogout', [AuthController::class, 'allLogout'])->name('auth.allLogout');
    });

    Route::middleware(['setUpAbilities'])->prefix('management')->as('management.')->group(function () {

        Route::apiResource('permissions', PermissionController::class)->only(['index', 'show']);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('roles.permissions', RolePermissionController::class)->except('update');

        Route::apiResource('users', UserController::class)->except('store');
        Route::apiResource('users.roles', UserRoleController::class)->except('update');

        Route::apiResource('categories', CategoryController::class);

        Route::apiResource('products', ProductController::class)->except('update');
        Route::post('products/{id}', [ProductController::class, 'update'])->name('products.update');

        Route::apiResource('shops', ShopController::class);
        Route::apiResource('shops.products', ShopProductController::class);
        Route::apiResource('schedules', ScheduleController::class);

        Route::prefix('configurations')->as('configurations.')->group(function () {
            Route::apiResource('site', SiteConfigurationController::class)->only(['index', 'show', 'update']);
        });

        Route::prefix('documents')->as('documents.')->group(function () {
            Route::apiResource('locations', DocumentLocationController::class)->only(['index', 'show', 'update']);
        });
        Route::apiResource('/documents', DocumentController::class);
    });
});
