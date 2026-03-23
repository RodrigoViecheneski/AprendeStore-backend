<?php

namespace Database\Seeders;

use App\Models\Address;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AddressSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Address::create([
            'zipcode' => '12345-678',
            'street' => 'Rua Exemplo',
            'number' => '123',
            'city' => 'Cidade Exemplo',
            'state' => 'Estado Exemplo',
            'country' => 'País Exemplo',
            'complement' => 'Complemento Exemplo',
            'user_id' => 1, // Certifique-se de que este ID corresponda a um usuário existente
        ]);
    }
}
