<?php

namespace App\Repositories\Interface;

interface ICategory
{
    public function get($limit);
    public function save($model);
    public function delete($model);
    public function update($model);
}
