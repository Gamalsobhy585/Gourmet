<?php

namespace App\Services\Interface;

interface ICategoryService
{
    public function getCategories($request);
    public function store($request);
    public function update($request,$model);
    public function delete($model);
}
