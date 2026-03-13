<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'label' => 'Camiseta (Laravel Azul)',
            'description' => 'Camiseta do laravel e azul!',
            'price' => 39.99,
            'views_count' => 10,
            'sales_count' => 20,
            'category_id' => 1
        ]);
        Product::create([
            'label' => 'Produto de Exemplo2',
            'description' => 'Descrição do Produto de Exemplo2',
            'price' => 19.99,
            'views_count' => 0,
            'sales_count' => 0,
            'category_id' => 1
        ]);
    }
}
