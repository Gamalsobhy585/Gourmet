<?php

namespace App\Repositories\Interface;

interface IProduct
{
    public function get($filter,$query,$limit);
    public function show($model);
    public function save($model);
    public function delete($model);
    public function update($model);
    public function findById($id);

}
