<?php

namespace App\Services\Interface;

interface IProductService
{
    public function getProducts($request, $limit);
    public function store($request);
    public function update($request,$model);
    public function delete($model);
}
