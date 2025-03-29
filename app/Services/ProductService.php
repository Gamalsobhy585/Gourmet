<?php

namespace App\Services;

use App\Services\Interface\IProductService;
use App\Repositories\Interface\IProduct;
use App\Traits\ResponseTrait;
use Illuminate\Support\Facades\Log;
use App\Http\Resources\ProductResource;

class ProductService implements IProductService
{
    use ResponseTrait;

    private IProduct $ProductRepo;

    public function __construct(IProduct $ProductRepo)
    {
        $this->ProductRepo = $ProductRepo;
    }

    public function getProducts($request, $limit)
    {
        $query = $request->input('query', '');
        $filters = [
            'category' => $request->input('category'),
            'sku' => $request->input('sku'),
            'price' => $request->input('price'),
            'price_range' => $request->input('price_range'),
            'created_date' => $request->input('created_date'),
            'created_date_range' => $request->input('created_date_range'),
        ];
        
        $products = $this->ProductRepo->get($filters, $query, $limit);
    
        return $this->returnData(
            __('messages.product.index_success'),
            200,
            ProductResource::collection($products)
        );
    }

    public function store($request)
    {
        Log::info('Product Creation Request', $request->all());
        try {
          

            $productData = [
                'name' => $request->input('name'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'category_id' => $request->input('category_id'),
                'SKU' => $request->input('SKU'),

            ];

            $this->ProductRepo->save($productData);

            return $this->success(__('messages.product.create_success'), 201);
        } catch (\Exception $e) {
            Log::error('Product Create Error', ['error' => $e->getMessage()]);
            $this->returnError(__('messages.product.create_failed'), 500);
        }

    }

    public function update($request,$id)
    {
        $existingProduct = $this->ProductRepo->findById($id);
        try {
            Log::info('Update Request Data:', $request->all());

            $existingProduct->title = $request->title ?? $existingProduct->title;
            $existingProduct->duration = $request->duration ?? $existingProduct->duration;
            $existingProduct->description = $request->description ?? $existingProduct->description;
            $existingProduct->reminders = $request->reminders ?? $existingProduct->reminders;
            $existingProduct->image = $habitImage ?? $existingProduct->image;
            $this->ProductRepo->update($existingProduct);


            return $this->success(__('messages.product.update_success'), 200);
        } catch (\Exception $e) {
            Log::error('product Update Error', ['error' => $e->getMessage()]);
            $this->returnError(__('messages.product.update_failed'), 500);
        }

    }

     public function delete($model)
    {
        try {
            $this->ProductRepo->delete($model);

            return $this->success(__('messages.product.delete_success'), 204);
        } catch (\Exception $e) {
            Log::error('Product Delete Error', ['error' => $e->getMessage()]);
            $this->returnError(__('messages.product.delete_failed'), 500);
        }
    }
}
