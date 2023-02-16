<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentLocationController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCategoryController;
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

Route::get('configurations', [SiteConfigurationController::class, 'data'])->name('site_configurations.data');
Route::get('/documents', [DocumentController::class, 'getUsed'])->name('documents.index.used');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/products/categories', [ProductCategoryController::class, 'getAll'])->name('products.categories.getAll');
Route::get('/shops/products', [ShopProductController::class, 'getAll'])->name('shops.products.getAll');

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
});

Route::middleware(['auth:sanctum'])->namespace('\\')->group(function () {
    Route::get('profile/permissions', [PermissionController::class, 'profilePermissions'])->name('profile.permissions');

    Route::prefix('auth')->group(function () {
        Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');
        Route::post('logout/all', [AuthController::class, 'logoutAll'])->name('auth.logoutAll');
    });

    Route::middleware(['setUpAbilities'])->prefix('management')->as('management.')->group(function () {

        Route::apiResource('permissions', PermissionController::class)->only(['index', 'show']);
        Route::apiResource('roles', RoleController::class);
        Route::apiResource('roles.permissions', RolePermissionController::class)->except('update');

        Route::apiResource('users', UserController::class)->except('store');
        Route::apiResource('users.roles', UserRoleController::class)->except('update');

        Route::apiResource('categories', CategoryController::class);

        Route::get('/products/categories', [ProductCategoryController::class, 'getAll'])->name('products.categories.getAll');
        Route::apiResource('products', ProductController::class)->except('update');
        Route::post('products/{id}', [ProductController::class, 'update'])->name('products.update');

        Route::get('/shops/products', [ShopProductController::class, 'getAll'])->name('shops.products.getAll');
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
