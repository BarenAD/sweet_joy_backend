<?php


namespace App\Repositories;



use App\Models\InformationCategorieItem;
use App\Models\Item;
use Illuminate\Support\Facades\DB;

class ItemsRepository
{
    private function extractIdsFromCategoriesItem($itemCategories) {
        $resultArray = [];
        if (isset($itemCategories)) {
            foreach ($itemCategories as $category) {
                array_push($resultArray, $category->id_ci);
            }
        }
        return $resultArray;
    }

    private function extractNamePictureFromPath($path) {
        $explode = explode("/", $path);
        return end($explode);
    }

    public function getItems(int $id = null) {
        if (isset($id)) {
            $item = Item::findOrFail($id);
            $itemCategories = InformationCategorieItem::where('id_i', $id)->get();
            return [
                'item' => $item,
                'categories' => $this->extractIdsFromCategoriesItem($itemCategories)
            ];
        }
        $items = Item::all();
        $itemsCategories = InformationCategorieItem::all()->groupBy('id_i');
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
        $picture,
        string $name,
        string $composition,
        string $manufacturer,
        string $description,
        string $product_unit,
        array $categories_item
    ) {
        $PicturesItemsRepository = new PicturesItemsRepository();
        $newImage = $PicturesItemsRepository->savePictureForItem($name, $picture);
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
                $newItem = Item::create([
                    'picture' => $newImage['fullPathForPicture'],
                    'miniature_picture' => $newImage['fullPathForMiniPicture'],
                    'name' => $name,
                    'composition' => $composition,
                    'manufacturer' => $manufacturer,
                    'description' => $description,
                    'product_unit' => $product_unit
                ]);
                $resultCategories = [];
                foreach ($categories_item as $category) {
                    array_push($resultCategories, InformationCategorieItem::create([
                        'id_i' => $newItem->id,
                        'id_ci' => $category
                    ])->id_ci);
                }
                DB::commit();
                return [
                    'item' => $newItem,
                    'categories' => $resultCategories
                ];
            });
        } catch (\Exception $error) {
            $PicturesItemsRepository->deletePictureForItem($newImage['name']);
            throw new \Exception($error);
        }
    }

    public function changeItem(
        int $id,
        string $name,
        string $composition,
        string $manufacturer,
        string $description,
        string $product_unit,
        array $categories_item,
        $picture = null
    ) {
        $newImage = null;
        $PicturesItemsRepository = null;
        if (isset($picture)) {
            $PicturesItemsRepository = new PicturesItemsRepository();
            $newImage = $PicturesItemsRepository->savePictureForItem($name, $picture);
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
                $PicturesItemsRepository
            ) {
                $item = Item::findOrFail($id);
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
                $itemCategories = InformationCategorieItem::where('id_i', $id)->get();
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
                    array_push($resultCategories, InformationCategorieItem::create([
                        'id_i' => $item->id,
                        'id_ci' => $category
                    ])->id_ci);
                }
                if (isset($newImage)) {
                    $PicturesItemsRepository->deletePictureForItem($oldNamePicture);
                }
                DB::commit();
                return [
                    'item' => $item,
                    'categories' => $resultCategories
                ];
            });
        } catch (\Exception $error) {
            if (isset($newImage)) {
                $PicturesItemsRepository->deletePictureForItem($newImage['name']);
            }
            throw new \Exception($error);
        }
    }

    public function deleteItem(int $id) {
        $PicturesItemsRepository = new PicturesItemsRepository();
        $item = Item::findOrFail($id);
        $PicturesItemsRepository->deletePictureForItem($this->extractNamePictureFromPath($item->picture));
        return Item::findOrFail($id)->delete();
    }
}
