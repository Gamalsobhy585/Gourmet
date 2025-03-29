<?php

namespace App\Repositories\Interface;

interface ICategory
{
    public function get();
    public function save($model);
    public function delete($model);
    public function update($model);
    public function findById($id);

}
