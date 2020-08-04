<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateCategoryItem;
use App\Repositories\CategoryItemRepository;
use Illuminate\Http\Request;

class ProductCategoriesItemController extends Controller
{
    private $categoriesItemRepository;

    public function __construct(CategoryItemRepository $categoriesItemRepository)
    {
        $this->categoriesItemRepository = $categoriesItemRepository;
    }

    public function getCategories(Request $request) {
        return response($this->categoriesItemRepository->getCategories($request->get('id')), 200);
    }

    public function createCategory(ChangeOrCreateCategoryItem $request) {
        return response($this->categoriesItemRepository->createCategory($request->get('name')), 200);
    }

    public function changeCategory(ChangeOrCreateCategoryItem $request) {
        return response($this->categoriesItemRepository->changeCategory((int) $request->get('id'), $request->get('name')), 200);
    }

    public function deleteCategory(Request $request) {
        return response($this->categoriesItemRepository->deleteCategory($request->get('id')), 200);
    }
}
