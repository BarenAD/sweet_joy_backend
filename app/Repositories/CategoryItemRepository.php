<?php


namespace App\Repositories;


use App\Http\services\GeneratedAborting;
use App\Models\CategoryItem;
use App\Models\User;
use App\Policies\CategoryItemPolicy;

class CategoryItemRepository
{
    public static function getCategories(int $id = null) {
        if (isset($id)) {
            return CategoryItem::findOrFail($id);
        }
        return CategoryItem::all();
    }

    public static function createCategory(User $user, string $name) {
        if (CategoryItemPolicy::canCreate($user)) {
            CacheRepository::cacheProductsInfo('delete', 'categories');
            return CategoryItem::create([
                'name' => $name
            ]);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public static function changeCategory(User $user, int $id, string $name) {
        if (CategoryItemPolicy::canUpdate($user)) {
            $category = CategoryItem::findOrFail($id);
            $category->fill([
                'name' => $name
            ])->save();
            CacheRepository::cacheProductsInfo('delete', 'categories');
            return $category;
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public static function deleteCategory(User $user, int $id) {
        if (CategoryItemPolicy::canDelete($user)) {
            CacheRepository::cacheProductsInfo('delete', 'categories');
            return CategoryItem::findOrFail($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
