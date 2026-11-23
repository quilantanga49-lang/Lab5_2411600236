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
        // Demo users. All use the password: password
        $users = [
            [
                'name' => 'Store Manager',
                'email' => 'admin@sari.com',
                'role' => 'manager',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Maria Cashier',
                'email' => 'cashier@sari.com',
                'role' => 'staff',
                'email_verified_at' => now(),
            ],
            [
                'name' => 'Juan Helper',
                'email' => 'helper@sari.com',
                'role' => 'staff',
                'email_verified_at' => null,
            ],
        ];

        foreach ($users as $data) {
            $user = User::withTrashed()->updateOrCreate(
                ['email' => $data['email']],
                $data + [
                    'password' => Hash::make('password'),
                    'is_active' => true,
                    'deleted_at' => null,
                ]
            );
        }

        $manager = User::where('email', 'admin@sari.com')->firstOrFail();

        $this->call(ProductSeeder::class);

        InventoryTransaction::query()->delete();

        Product::all()->each(function (Product $product) use ($manager) {
            $product->transactions()->create([
                'type' => 'in',
                'quantity' => $product->quantity,
                'reference' => 'INITIAL-STOCK',
                'user_id' => $manager->id,
            ]);
        });
    }
}
