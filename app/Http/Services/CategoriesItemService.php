<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 15:30
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\CategoryItemPolicy;
use App\Repositories\CategoriesItemRepository;

/**
 * Class CategoriesItemService
 * @package App\Http\Services
 */
class CategoriesItemService
{
    private CategoriesItemRepository $categoriesItemService;
    private CategoryItemPolicy $categoryItemPolicy;

    public function __construct(
        CategoriesItemRepository $categoriesItemService,
        CategoryItemPolicy $categoryItemPolicy
    ) {
        $this->categoriesItemService = $categoriesItemService;
        $this->categoryItemPolicy = $categoryItemPolicy;
    }

    public function getCategories(int $id = null)
    {
        return $this->categoriesItemService->getCategoriesItem($id);
    }

    public function createCategory(User $user, string $name)
    {
        if ($this->categoryItemPolicy->canCreate($user)) {
            CacheService::cacheProductsInfo('delete', 'categories');
            return $this->categoriesItemService->create($name);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changeCategory(User $user, int $id, string $name)
    {
        if ($this->categoryItemPolicy->canUpdate($user)) {
            $category = $this->categoriesItemService->getCategoriesItem($id);
            $category->fill([
                'name' => $name
            ])->save();
            CacheService::cacheProductsInfo('delete', 'categories');
            return $category;
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function deleteCategory(User $user, int $id)
    {
        if ($this->categoryItemPolicy->canDelete($user)) {
            CacheService::cacheProductsInfo('delete', 'categories');
            return $this->categoriesItemService->getCategoriesItem($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
