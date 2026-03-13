<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategoryMetadataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $metadataTec = Category::first()->metadata()->create([
            'id' => 'tecnologia',
            'name' => 'Tecnologia'
        ]);
        $metadataCor = Category::first()->metadata()->create([
            'id' => 'cor',
            'name' => 'Cor'
        ]);
        $metadataCor->values()->create([
            'id' => 'preto',
            'label' => 'Preto'
        ]);
        $metadataCor->values()->create([
            'id' => 'azul',
            'label' => 'Azul'
        ]);
        $metadataTec->values()->create([
            'id' => 'laravel',
            'label' => 'Laravel'
        ]);
        $metadataTec->values()->create([
            'id' => 'react-native',
            'label' => 'React-Native'
        ]);
        Product::first()->metadata()->create([
            'category_metadata_id' => 'cor',
            'metadata_value_id' => 'azul'
        ]);
        Product::first()->metadata()->create([
            'category_metadata_id' => 'tecnologia',
            'metadata_value_id' => 'laravel'
        ]);
        Product::first()->metadata()->create([
            'category_metadata_id' => 'tecnologia',
            'metadata_value_id' => 'react-native'
        ]);
    }
}
