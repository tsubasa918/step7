<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run()
    {
        Product::create([
            'name' => 'Sample Product',
            'company_id' => 1,
            'price' => 10.99,
            'stock' => 100,
            'comment' => 'This is a sample product.',
            'image' => 'path/to/image.jpg',
        ]);
    }
}
