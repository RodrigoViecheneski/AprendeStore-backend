<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function register(Request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'O campo nome é obrigatório.',
                'email.required' => 'O campo email é obrigatório.',
                'email.email' => 'O campo email deve ser um endereço de email válido.',
                'email.unique' => 'O email já está em uso.',
                'password.required' => 'O campo senha é obrigatório.',
                'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'user' => null
            ], 400);
        }
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
        ]);
        return response()->json([
            'error' => null,
            'user' =>
            [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
            ]
        ]);
    }
    public function login(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'email' => ['required', 'string', 'email'],
                'password' => ['required', 'string'],
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => 'Usuário ou senha inválidos.',
                'token' => null
            ], 400);
        }
        $user = User::where('email', $request->input('email'))->first();
        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            return response()->json([
                'error' => 'Usuário ou senha inválidos.',
                'token' => null
            ], 400);
        }
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'error' => null,
            'token' => $token
        ]);
    }
    public function createAddress(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'zipcode' => ['required', 'string', 'max:20'],
                'street' => ['required', 'string', 'max:255'],
                'number' => ['required', 'string', 'max:20'],
                'city' => ['required', 'string', 'max:100'],
                'state' => ['required', 'string', 'max:100'],
                'country' => ['required', 'string', 'max:100'],
                'complement' => ['sometimes', 'nullable', 'string', 'max:255'],
            ],
            [
                'zipcode.required' => 'O campo CEP é obrigatório.',
                'street.required' => 'O campo rua é obrigatório.',
                'number.required' => 'O campo número é obrigatório.',
                'city.required' => 'O campo cidade é obrigatório.',
                'state.required' => 'O campo estado é obrigatório.',
                'country.required' => 'O campo país é obrigatório.',
            ]
        );
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first(),
                'address' => null
            ], 400);
        }
        $user = Auth::user();
        //dd($user);
        //$user->addresses->create($request->only('zipcode', 'street', 'number', 'city', 'state', 'country', 'complement'));
        $address = Address::create([
            'user_id' => $user->id,
            'zipcode' => $request->input('zipcode'),
            'street' => $request->input('street'),
            'number' => $request->input('number'),
            'city' => $request->input('city'),
            'state' => $request->input('state'),
            'country' => $request->input('country'),
            'complement' => $request->input('complement'),
        ]);
        return response()->json([
            'error' => null,
            'address' => [
                'id' => $address->id,
                'zipcode' => $address->zipcode,
                'street' => $address->street,
                'number' => $address->number,
                'city' => $address->city,
                'state' => $address->state,
                'country' => $address->country,
                'complement' => $address->complement,
            ]
        ]);
    }
    public function getAddresses(Request $request)
    {
        $user = Auth::user();

        $user = User::find($user->id);
        $addresses = $user->addresses()->get();

        return response()->json([
            'error' => null,
            'addresses' => $addresses
        ]);
    }
}
