<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $products = [
            // Category One Products
            [
                'name' => 'Product 1A',
                'price' => 10,
                'SKU' => 'CAT1-001',
                'description' => 'Description for Product 1A',
                'category_id' => 1
            ],
            [
                'name' => 'Product 2A',
                'price' => 30,
                'SKU' => 'CAT1-002',
                'description' => 'Description for Product 2A',
                'category_id' => 1
            ],
            [
                'name' => 'Product 3A',
                'price' => 40,
                'SKU' => 'CAT1-003',
                'description' => 'Description for Product 3A',
                'category_id' => 1
            ],
            [
                'name' => 'Product 4A',
                'price' => 50,
                'SKU' => 'CAT1-004',
                'description' => 'Description for Product 4A',
                'category_id' => 1
            ],
            [
                'name' => 'Product 5A',
                'price' => 60,
                'SKU' => 'CAT1-005',
                'description' => 'Description for Product 5A',
                'category_id' => 1
            ],

            // Category Two Products
            [
                'name' => 'Product 1B',
                'price' => 25,
                'SKU' => 'CAT2-001',
                'description' => 'Description for Product 1B',
                'category_id' => 2
            ],
            [
                'name' => 'Product 2B',
                'price' => 30,
                'SKU' => 'CAT2-002',
                'description' => 'Description for Product 2B',
                'category_id' => 2
            ],
            [
                'name' => 'Product 3B',
                'price' => 45,
                'SKU' => 'CAT2-003',
                'description' => 'Description for Product 3B',
                'category_id' => 2
            ],
            [
                'name' => 'Product 4B',
                'price' => 55,
                'SKU' => 'CAT2-004',
                'description' => 'Description for Product 4B',
                'category_id' => 2
            ],
            [
                'name' => 'Product 5B',
                'price' => 65,
                'SKU' => 'CAT2-005',
                'description' => 'Description for Product 5B',
                'category_id' => 2
            ],

            // Category Three Products
            [
                'name' => 'Product 1C',
                'price' => 28,
                'SKU' => 'CAT3-001',
                'description' => 'Description for Product 1C',
                'category_id' => 3
            ],
            [
                'name' => 'Product 2C',
                'price' => 38,
                'SKU' => 'CAT3-002',
                'description' => 'Description for Product 2C',
                'category_id' => 3
            ],
            [
                'name' => 'Product 3C',
                'price' => 48,
                'SKU' => 'CAT3-003',
                'description' => 'Description for Product 3C',
                'category_id' => 3
            ],
            [
                'name' => 'Product 4C',
                'price' => 58,
                'SKU' => 'CAT3-004',
                'description' => 'Description for Product 4C',
                'category_id' => 3
            ],
            [
                'name' => 'Product 5C',
                'price' => 68,
                'SKU' => 'CAT3-005',
                'description' => 'Description for Product 5C',
                'category_id' => 3
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}