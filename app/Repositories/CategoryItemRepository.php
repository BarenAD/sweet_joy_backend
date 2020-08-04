<?php


namespace App\Repositories;


use App\Models\CategoryItem;

class CategoryItemRepository
{
    public function getCategories(int $id = null) {
        if (isset($id)) {
            return CategoryItem::findOrFail($id);
        }
        return CategoryItem::all();
    }

    public function createCategory(string $name) {
        return CategoryItem::create([
            'name' => $name
        ]);
    }

    public function changeCategory(int $id, string $name) {
        $category = CategoryItem::findOrFail($id);
        $category->fill([
            'name' => $name
        ])->save();
        return $category;
    }

    public function deleteCategory(int $id) {
        return CategoryItem::findOrFail($id)->delete();
    }
}
