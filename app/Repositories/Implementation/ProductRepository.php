<?php
namespace App\Repositories\Implementation;
use App\Models\Product;
use App\Repositories\Interface\IProduct;
class ProductRepository implements IProduct
{
    public function get($filter, $query, $limit, $sort_by = null, $sort_direction = 'desc')
    {
        $sort_direction = in_array(strtolower($sort_direction), ['asc', 'desc']) ? strtolower($sort_direction) : 'desc';
        $sortMap = [
            'category' => 'categories.id',
            'sku' => 'products.sku',
            'price' => 'products.price',
            'created_date' => 'products.created_at',
            'default' => 'products.created_at'
        ];
        $sortColumn = $sortMap['default'];
        if (isset($sort_by) && array_key_exists($sort_by, $sortMap)) {
            $sortColumn = $sortMap[$sort_by];
        }
        return Product::with('category') 
            ->select([
                'products.id',
                'products.sku',
                'products.name',
                'products.description',
                'categories.name as category_name',
                'products.price',
                'products.created_at',
                'products.category_id'
            ])
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->when(isset($filter['category']), function ($q) use ($filter) {
                $q->where('categories.name', 'like', '%' . $filter['category'] . '%');
            })
            ->when(isset($filter['sku']), function ($q) use ($filter) {
                $q->where('products.sku', 'like', '%' . $filter['sku'] . '%');
            })
            ->when(isset($filter['price']), function ($q) use ($filter) {
                $q->where('products.price', $filter['price']);
            })
            ->when(isset($filter['price_range']), function ($q) use ($filter) {
                $range = explode('-', $filter['price_range']);
                if (count($range) === 2) {
                    $q->whereBetween('products.price', [(float)$range[0], (float)$range[1]]);
                }
            })
            ->when(isset($filter['created_date']), function ($q) use ($filter) {
                $q->whereDate('products.created_at', $filter['created_date']);
            })
            ->when(isset($filter['created_date_range']), function ($q) use ($filter) {
                $dates = explode(' to ', $filter['created_date_range']);
                if (count($dates) === 2) {
                    $q->whereBetween('products.created_at', [$dates[0], $dates[1]]);
                }
            })
            ->when($query, function ($q) use ($query) {
                $q->where(function ($subQuery) use ($query) {
                    $subQuery->where('products.name', 'like', '%' . $query . '%')
                              ->orWhere('products.description', 'like', '%' . $query . '%');
                });
            })
            ->orderBy($sortColumn, $sort_direction)
            ->paginate($limit);
    }
    public function show($model)
    {
        return Product::with('category')
            ->select([
                'products.id',
                'products.sku',
                'products.name',
                'products.description',
                'categories.name as category_name',
                'products.price',
                'products.price_in_store_b',
                'products.created_at'
            ])
            ->leftJoin('categories', 'products.category_id', '=', 'categories.id')
            ->find($model->id);
    }
    public function save($model)
    {
        return Product::create($model);
    }
    public function delete($model)
    {
        return $model->delete();
    }
    public function update($model)
    {
        return $model->save();
    }
    public function findById($id)
    {
        return Product::findOrFail($id);
    }
}