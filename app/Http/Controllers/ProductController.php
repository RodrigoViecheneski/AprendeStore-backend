<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function getAllProducts(Request $request)
    {
        $validator = Validator::make($request->query(), [
            'metadata' => ['sometimes', 'string'],
            'orderBy' => ['sometimes', 'in:views,selling,price'],
            'limit' => ['sometimes', 'numeric'],
        ], [
            'metadata' => 'O campo metadata deve ser um campo válido.',
            'orderBy' => 'O campo orderBy deve ser um dos seguintes valores: views, selling, price.',
            'limit' => 'O campo limit deve ser um número válido.',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }
        #-----------------------------------------------------------------------------------------
        #VALIDAÇÃO COM JSON
        $metadata = [];

        if ($request->filled('metadata')) {
            $rawMetadata = $request->query('metadata');
            $metadata = json_decode($rawMetadata, true);
            //dd($metadata);
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'O campo metadata deve ser um JSON válido.',
                    'products' => []
                ], 400);
            }
        }
        #-----------------------------------------------------------------------------------------

        $limit = $request->query('limit', 10);
        $orderBy = 'id';
        # Mudanças aqui para filtrar produtos com base na metadata
        $query = Product::query()->with('metadata');
        foreach ($metadata as $key => $value) {
            $query->whereHas('metadata', function ($q) use ($key, $value) {
                $q->where('category_metadata_id', $key)
                    ->where('metadata_value_id', $value);
            });
        }


        if ($request->query('orderBy')) {
            switch ($request->query('orderBy')) {
                case 'views':
                    $orderBy = 'views_count';
                    break;
                case 'selling':
                    $orderBy = 'sales_count';
                    break;
                case 'price':
                    $orderBy = 'price';
                    break;
            }
        }
        $query->orderBy($orderBy, 'desc');
        $query->with('images');
        $query->limit($limit);

        $products = $query->get();
        return response()->json([
            'error' => null,
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'label' => $product->label,
                    'price' => $product->price,
                    'images' => asset('storage/' . ($product->images->first()->url ?? '/products/default-product.svg')),
                    'liked' => false // TODO: IMPLEMENTAR O LIKED
                ];
            })
        ]);
    }
}
