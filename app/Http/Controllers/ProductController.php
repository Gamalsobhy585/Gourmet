<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private ProductService $ProductService;

    public function __construct(ProductService $ProductService)
    {
        $this->ProductService = $ProductService;
    }

    public function index(Request $request)
    {
        return $this->ProductService->getProducts($request, $request->get("per_page", 20));
    }



    public function store(StoreProductRequest $request)
    {
        return $this->ProductService->store($request);
    }

    public function update(UpdateProductRequest $request, $id)
    {
        return $this->ProductService->update($request, $id);
    }

    public function delete(Product $product)
    {
        return $this->ProductService->delete($product);
    }
}
