<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

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
    public function finish(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'cart' => ['required', 'array', 'min:1'],
                'cart.*.productId' => ['required', 'numeric', 'min:1'],
                'cart.*.quantity' => ['required', 'numeric', 'min:1'],
                'addressId' => ['required', 'numeric', 'min:1'],
            ],
            [
                'cart.required' => 'O campo cart é obrigatório.',
                'cart.array' => 'O campo cart deve ser um array.',
                'cart.min' => 'O campo cart deve ter ao menos 1 item.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'products' => []
            ], 400);
        };
        $user = Auth::user();
        //dd($user);

        // Verificar se o endereço pertence ao usuário
        $address = Address::find($request->input('addressId'));
        if ($address->user_id !== $user->id) {
            return response()->json([
                'error' => 'Endereço não pertence ao usuário.',
                'products' => []
            ], 400);
        }
        //Pegar todos os produtos do carrinho
        $cart = $request->input('cart');
        $products = Product::whereIn('id', array_column($cart, 'productId'))->get();
        //dd($products);

        //Calcular o total do corrinho
        $total = 0;
        foreach ($cart as $item) {
            $product = $products->find($item['productId']);
            $total += $product->price * $item['quantity'];
        }
        //dd($total);
        $order = Order::create([
            'user_id' => $user->id,
            'total' => $total,
            'shippingCost' => 7,
            'shippingDays' => 3,
            'shippingZipcode' => $address->zipcode,
            'shippingStreet' => $address->street,
            'shippingNumber' => $address->number,
            'shippingCity' => $address->city,
            'shippingState' => $address->state,
            'shippingCountry' => $address->country,
            'shippingComplement' => $address->complement
        ]);
        foreach ($cart as $item) {
            $product = $products->find($item['productId']);
            $order->products()->create([
                'product_id' => $product->id,
                'quantity' => $item['quantity'],
                'price' => $product->price
            ]);
        }
    }
}
