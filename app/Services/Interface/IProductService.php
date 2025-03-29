<?php

namespace App\Services\Interface;

interface IProductService
{
    public function getProducts($request, $limit);
    public function create($request);
    public function update($request,$model);
    public function delete($model);
}
