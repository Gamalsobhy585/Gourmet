<?php

namespace App\Repositories\Implementation;

use App\Models\Category;
use App\Repositories\Interface\ICategory;

class CategoryRepository implements ICategory
{
    public function get()
    {
        return Category::all();
    }

    
    public function save($model)
    {
        return Category::create($model);
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
        return Category::findOrFail($id);
    }
}
