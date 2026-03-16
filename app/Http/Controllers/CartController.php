<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;

class CartController extends Controller
{
    public function mount(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'ids' => ['required', 'array', 'min:1'],
                'ids.*' => ['numeric', 'min:1']
            ],
            [
                'ids.required' => 'O campo ids é obrigatório.',
                'ids.array' => 'O campo ids deve ser um array.',
                'ids.min' => 'O campo ids deve ter ao menos 1 item.',
                'ids.*.numeric' => 'Cada ID deve ser um número.',
                'ids.*.min' => 'Cada ID deve ser um número maior que zero.'
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }
        $ids = $request->input('ids');

        $products = Product::with('images')->whereIn('id', $ids)->get();
        //dd($products);
        $formattedProducts = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'label' => $product->label,
                'price' => $product->price,
                'images' => asset('storage/' . ($product->images->first()->url ?? 'products/default-product.png')),
            ];
        });
        return response()->json([
            'error' => null,
            'products' => $formattedProducts
        ]);
    }
    public function shipping(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'zipcode' => ['required', 'min:4'],
            ],
            [
                'zipcode' => 'O campo zipcode é obrigatório.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        }
        return response()->json([
            'error' => null,
            'shipping' => [
                'zipcode' => $request->input('zipcode'),
                'price' => 7,
                'days' => 3
            ]
        ]);
    }
}
