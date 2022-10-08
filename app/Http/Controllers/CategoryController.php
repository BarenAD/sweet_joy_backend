<?php

namespace App\Http\Controllers;

use App\Http\Requests\Categories\CategoryDestroyRequest;
use App\Http\Requests\Categories\CategoryIndexRequest;
use App\Http\Requests\Categories\CategoryStoreRequest;
use App\Http\Requests\Categories\CategoryUpdateRequest;
use App\Repositories\CategoryRepository;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    private CategoryRepository $categoriesRepository;

    public function __construct(
        CategoryRepository $categoriesRepository
    ) {
        $this->categoriesRepository = $categoriesRepository;
    }

    public function index(CategoryIndexRequest $request): Response
    {
        return response($this->categoriesRepository->getAll(), 200);
    }

    public function show(CategoryIndexRequest $request, int $id): Response
    {
        return response($this->categoriesRepository->find($id), 200);
    }

    public function store(CategoryStoreRequest $request): Response
    {
        return response($this->categoriesRepository->store($request->validated()), 200);
    }

    public function update(CategoryUpdateRequest $request, int $id): Response
    {
        return response($this->categoriesRepository->update($id, $request->validated()), 200);
    }

    public function destroy(CategoryDestroyRequest $request, int $id): Response
    {
        return response($this->categoriesRepository->destroy($id), 200);
    }

}
