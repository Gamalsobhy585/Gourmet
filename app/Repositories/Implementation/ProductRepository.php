<?php

namespace App\Repositories\Implementation;

use App\Models\Product;
use App\Repositories\Interface\IProduct;

class ProductRepository implements IProduct
{
    public function get($filter,$query,$limit)
    {
        return Product::where('filter', $filter)
        ->paginate($limit);
    }

    public function show($model)
    {
        return Product::find($model->id);
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
        return Product::find($id);
    }
}
