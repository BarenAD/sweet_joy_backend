<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChangeOrCreateCategoryItem;
use App\Http\Services\CategoriesItemService;
use Illuminate\Http\Request;

/**
 * Class ProductCategoriesItemController
 * @package App\Http\Controllers
 */
class ProductCategoriesItemController extends Controller
{
    private $categoriesItemService;

    /**
     * ProductCategoriesItemController constructor.
     * @param CategoriesItemService $categoriesItemService
     */
    public function __construct(CategoriesItemService $categoriesItemService)
    {
        $this->categoriesItemService = $categoriesItemService;
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function getCategories(Request $request) {
        return response($this->categoriesItemService->getCategories($request->get('id')), 200);
    }

    /**
     * @param ChangeOrCreateCategoryItem $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function createCategory(ChangeOrCreateCategoryItem $request) {
        return response($this->categoriesItemService->createCategory($request->user(), $request->get('name')), 200);
    }

    /**
     * @param ChangeOrCreateCategoryItem $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function changeCategory(ChangeOrCreateCategoryItem $request) {
        return response($this->categoriesItemService->changeCategory($request->user(), (int) $request->get('id'), $request->get('name')), 200);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function deleteCategory(Request $request) {
        return response($this->categoriesItemService->deleteCategory($request->user(), $request->get('id')), 200);
    }
}
