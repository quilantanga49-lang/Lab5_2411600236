<?php

namespace Database\Seeders;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['email' => 'admin@sari.com'],
            [
                'name' => 'Store Manager',
                'role' => 'manager',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]
        );

        $this->call(ProductSeeder::class);

        InventoryTransaction::query()->delete();

        Product::all()->each(function (Product $product) use ($user) {
            $product->transactions()->create([
                'type' => 'in',
                'quantity' => $product->quantity,
                'reference' => 'INITIAL-STOCK',
                'user_id' => $user->id,
            ]);
        });
    }
}
