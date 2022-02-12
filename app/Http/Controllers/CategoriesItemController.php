<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateCategoryItem;
use App\Http\Services\CategoriesItemService;
use Illuminate\Http\Request;

/**
 * Class ProductCategoriesItemController
 * @package App\Http\Controllers
 */
class CategoriesItemController extends Controller
{
    private CategoriesItemService $categoriesItemService;

    public function __construct(CategoriesItemService $categoriesItemService)
    {
        $this->categoriesItemService = $categoriesItemService;
    }

    public function getCategories(Request $request, int $id = null)
    {
        return response($this->categoriesItemService->getCategories($id), 200);
    }

    public function createCategory(ChangeOrCreateCategoryItem $request)
    {
        return response($this->categoriesItemService->createCategory($request->user(), $request->get('name')), 200);
    }

    public function changeCategory(ChangeOrCreateCategoryItem $request, int $id)
    {
        return response($this->categoriesItemService->changeCategory($request->user(), $id, $request->get('name')), 200);
    }

    public function deleteCategory(Request $request, int $id)
    {
        return response($this->categoriesItemService->deleteCategory($request->user(), $id), 200);
    }
}
