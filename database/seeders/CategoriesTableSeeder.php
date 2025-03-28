<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    public function run()
    {
        $categories = [
            [
                'name' => 'Category One',
                'B_percentage' => 5.00
            ],
            [
                'name' => 'Category Two',
                'B_percentage' => 10.00
            ],
            [
                'name' => 'Category Three',
                'B_percentage' => 15.00
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}