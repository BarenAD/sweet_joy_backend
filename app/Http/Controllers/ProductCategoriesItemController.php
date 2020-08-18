<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateCategoryItem;
use App\Repositories\CategoryItemRepository;
use Illuminate\Http\Request;

class ProductCategoriesItemController extends Controller
{
    public function getCategories(Request $request) {
        return response(CategoryItemRepository::getCategories($request->get('id')), 200);
    }

    public function createCategory(ChangeOrCreateCategoryItem $request) {
        return response(CategoryItemRepository::createCategory($request->user(), $request->get('name')), 200);
    }

    public function changeCategory(ChangeOrCreateCategoryItem $request) {
        return response(CategoryItemRepository::changeCategory($request->user(), (int) $request->get('id'), $request->get('name')), 200);
    }

    public function deleteCategory(Request $request) {
        return response(CategoryItemRepository::deleteCategory($request->user(), $request->get('id')), 200);
    }
}
