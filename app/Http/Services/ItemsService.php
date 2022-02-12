<?php
/**
 * Created by PhpStorm.
 * User: barenad
 * Date: 23.08.21
 * Time: 16:08
 */

namespace App\Http\Services;


use App\Models\User;
use App\Policies\ItemPolicy;
use App\Repositories\InformationCategoriesItemRepository;
use App\Repositories\ItemsRepository;
use Illuminate\Support\Facades\DB;

/**
 * Class ItemsService
 * @package App\Http\Services
 */
class ItemsService
{
    private ItemsRepository $itemsRepository;
    private InformationCategoriesItemRepository $informationCategoriesItemRepository;
    private ItemPolicy $itemPolicy;

    public function __construct(
        ItemsRepository $itemsRepository,
        InformationCategoriesItemRepository $informationCategoriesItemRepository,
        ItemPolicy $itemPolicy
    ){
        $this->itemsRepository = $itemsRepository;
        $this->informationCategoriesItemRepository = $informationCategoriesItemRepository;
        $this->itemPolicy = $itemPolicy;
    }

    private function extractIdsFromCategoriesItem($itemCategories)
    {
        $resultArray = [];
        if (isset($itemCategories)) {
            foreach ($itemCategories as $category) {
                array_push($resultArray, $category->id_ci);
            }
        }
        return $resultArray;
    }

    private function extractNamePictureFromPath($path)
    {
        $explode = explode("/", $path);
        return end($explode);
    }

    public function getItems(int $id = null)
    {
        if (isset($id)) {
            $item = $this->itemsRepository->getItems($id);
            $itemCategories = $this->informationCategoriesItemRepository->getInfoCategoriesOnIDItem($id);
            return [
                'item' => $item,
                'categories' => $this->extractIdsFromCategoriesItem($itemCategories)
            ];
        }
        $items = $this->itemsRepository->getItems();
        $itemsCategories = $this->informationCategoriesItemRepository->getInfoCategoriesGroupByItem();
        $resultArray = [];
        foreach ($items as $item) {
            array_push($resultArray, [
                'item' => $item,
                'categories' => $this->extractIdsFromCategoriesItem($itemsCategories[$item->id])
            ]);
        }
        return $resultArray;
    }

    public function createItem(
        User $user,
        $picture,
        string $name,
        string $composition = null,
        string $manufacturer = null,
        string $description = null,
        string $product_unit = null,
        array $categories_item = []
    ) {
        if ($this->itemPolicy->canCreate($user)) {
            $PicturesItemsService = new PicturesItemsService();
            $newImage = $PicturesItemsService->savePictureForItem($name, $picture);
            try {
                return DB::transaction(function () use (
                    $name,
                    $composition,
                    $manufacturer,
                    $description,
                    $product_unit,
                    $categories_item,
                    $newImage
                ) {
                    $newItem = $this->itemsRepository->create(
                        $newImage['fullPathForPicture'],
                        $newImage['fullPathForMiniPicture'],
                        $name,
                        $composition,
                        $manufacturer,
                        $description,
                        $product_unit
                    );
                    $resultCategories = [];
                    foreach ($categories_item as $category) {
                        array_push($resultCategories, $this->informationCategoriesItemRepository->create($newItem->id, $category)->id_ci);
                    }
                    DB::commit();
                    CacheService::cacheProductsInfo('delete', 'items');
                    return [
                        'item' => $newItem,
                        'categories' => $resultCategories
                    ];
                });
            } catch (\Exception $error) {
                $PicturesItemsService->deletePictureForItem($newImage['name']);
                throw new \Exception($error);
            }
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function changeItem(
        User $user,
        int $id,
        string $name,
        string $composition = null,
        string $manufacturer = null,
        string $description = null,
        string $product_unit = null,
        array $categories_item = [],
        $picture = null
    ) {
        if ($this->itemPolicy->canUpdate($user)) {
            $newImage = null;
            $PicturesItemsService = null;
            if (isset($picture)) {
                $PicturesItemsService = new PicturesItemsService();
                $newImage = $PicturesItemsService->savePictureForItem($name, $picture);
            }
            try {
                return DB::transaction(function () use (
                    $id,
                    $name,
                    $composition,
                    $manufacturer,
                    $description,
                    $product_unit,
                    $categories_item,
                    $newImage,
                    $PicturesItemsService
                ) {
                    $item = $this->itemsRepository->getItems($id);
                    $oldNamePicture = $this->extractNamePictureFromPath($item->picture);
                    if (isset($newImage)) {
                        $item->fill([
                            'picture' => $newImage['fullPathForPicture'],
                            'miniature_picture' => $newImage['fullPathForMiniPicture'],
                        ]);
                    }
                    $item->fill([
                        'name' => $name,
                        'composition' => $composition,
                        'manufacturer' => $manufacturer,
                        'description' => $description,
                        'product_unit' => $product_unit,
                    ])->save();
                    $itemCategories = $this->informationCategoriesItemRepository->getInfoCategoriesOnIDItem($id);
                    $resultCategories = [];
                    foreach ($itemCategories as $category) {
                        $key = array_search($category->id_ci, $categories_item);
                        if ($key === false) {
                            $category->delete();
                        } else {
                            unset($categories_item[$key]);
                            array_push($resultCategories, $category->id_ci);
                        }
                    }
                    foreach ($categories_item as $category) {
                        array_push($resultCategories, $this->informationCategoriesItemRepository->create($item->id, $category)->id_ci);
                    }
                    if (isset($newImage)) {
                        $PicturesItemsService->deletePictureForItem($oldNamePicture);
                    }
                    DB::commit();
                    CacheService::cacheProductsInfo('delete', 'items');
                    return [
                        'item' => $item,
                        'categories' => $resultCategories
                    ];
                });
            } catch (\Exception $error) {
                if (isset($newImage)) {
                    $PicturesItemsService->deletePictureForItem($newImage['name']);
                }
                throw new \Exception($error);
            }
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }

    public function deleteItem(User $user, int $id)
    {
        if ($this->itemPolicy->canDelete($user)) {
            $PicturesItemsService = new PicturesItemsService();
            $item = $this->itemsRepository->getItems($id);
            $PicturesItemsService->deletePictureForItem($this->extractNamePictureFromPath($item->picture));
            CacheService::cacheProductsInfo('delete', 'items');
            return $item->delete();
        } else {
            GeneratedAborting::accessDeniedGrandsAdmin();
        }
    }
}
