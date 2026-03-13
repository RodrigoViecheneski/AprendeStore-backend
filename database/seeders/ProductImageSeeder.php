<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $product = Product::first(); // Obtém o primeiro produto
        $product->images()->create([
            'url' => '/products/product_1_1.jpg',
        ]);
    }
}
