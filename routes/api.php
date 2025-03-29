<?php

use Illuminate\Support\Facades\Route;

Route::middleware(["lang", "cors"])->group(function () {
    require __DIR__ . '/api/product.php';
    require __DIR__ . '/api/category.php';
});
