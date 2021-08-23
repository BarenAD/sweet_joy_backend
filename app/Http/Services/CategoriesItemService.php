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
    private $categoriesItemService;

    /**
     * CategoriesItemService constructor.
     * @param CategoriesItemRepository $categoriesItemService
     */
    public function __construct(CategoriesItemRepository $categoriesItemService)
    {
        $this->categoriesItemService = $categoriesItemService;
    }

    /**
     * @param int|null $id
     * @return CategoriesItemRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCategories(int $id = null) {
        return $this->categoriesItemService->getCategoriesItem($id);
    }

    /**
     * @param User $user
     * @param string $name
     * @return mixed
     */
    public function createCategory(User $user, string $name) {
        if (CategoryItemPolicy::canCreate($user)) {
            CacheService::cacheProductsInfo('delete', 'categories');
            return $this->categoriesItemService->create($name);
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    /**
     * @param User $user
     * @param int $id
     * @param string $name
     * @return CategoriesItemRepository[]|\Illuminate\Database\Eloquent\Collection
     */
    public function changeCategory(User $user, int $id, string $name) {
        if (CategoryItemPolicy::canUpdate($user)) {
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

    /**
     * @param User $user
     * @param int $id
     * @return mixed
     */
    public function deleteCategory(User $user, int $id) {
        if (CategoryItemPolicy::canDelete($user)) {
            CacheService::cacheProductsInfo('delete', 'categories');
            return $this->categoriesItemService->getCategoriesItem($id)->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
