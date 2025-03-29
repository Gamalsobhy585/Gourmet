<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\Interface\IProduct;
use App\Repositories\Implementation\ProductRepository;
use App\Services\Interface\IProductService;
use App\Services\ProductService;
use App\Repositories\Interface\ICategory;
use App\Repositories\Implementation\CategoryRepository;
use App\Services\Interface\ICategoryService;
use App\Services\CategoryService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(IProduct::class, ProductRepository::class);
        $this->app->bind(IProductService::class, ProductService::class);
        $this->app->bind(ICategory::class, CategoryRepository::class);
        $this->app->bind(ICategoryService::class, CategoryService::class);
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
