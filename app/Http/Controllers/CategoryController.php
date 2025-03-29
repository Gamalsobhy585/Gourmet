<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    private CategoryService $CategoryService;

    public function __construct(CategoryService $CategoryService)
    {
        $this->CategoryService = $CategoryService;
    }

    public function index(Request $request)
    {
        return $this->CategoryService->getCategories($request);
    }



    public function store(StoreCategoryRequest $request)
    {
        return $this->CategoryService->store($request);
    }

    public function update(UpdateCategoryRequest $request, $id)
    {
        return $this->CategoryService->update($request, $id);
    }

    public function delete(Category $category)
    {
        return $this->CategoryService->delete($category);
    }
}
