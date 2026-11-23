<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Coca-Cola 330ml', 'sku' => 'BEV-001', 'category' => 'Beverages', 'brand' => 'Coca-Cola', 'quantity' => 48, 'reorder_level' => 12, 'unit_price' => 25.00, 'supplier' => 'Metro Drinks Depot', 'description' => 'Chilled soft drink, 330ml can.'],
            ['name' => 'Sprite 330ml', 'sku' => 'BEV-002', 'category' => 'Beverages', 'brand' => 'Sprite', 'quantity' => 8, 'reorder_level' => 12, 'unit_price' => 25.00, 'supplier' => 'Metro Drinks Depot', 'description' => 'Lemon-lime soft drink, 330ml can.'],
            ['name' => 'Royal Tru-Orange 1L', 'sku' => 'BEV-003', 'category' => 'Beverages', 'brand' => 'Royal', 'quantity' => 0, 'reorder_level' => 6, 'unit_price' => 65.00, 'supplier' => 'Metro Drinks Depot', 'description' => 'Orange soda, 1 liter bottle.'],
            ['name' => 'Indomie Chicken Noodles', 'sku' => 'NOO-001', 'category' => 'Noodles', 'brand' => 'Indomie', 'quantity' => 72, 'reorder_level' => 24, 'unit_price' => 18.00, 'supplier' => 'Suki Wholesale', 'description' => 'Instant noodles, chicken flavor.'],
            ['name' => 'Lucky Me Beef Noodles', 'sku' => 'NOO-002', 'category' => 'Noodles', 'brand' => 'Lucky Me', 'quantity' => 10, 'reorder_level' => 24, 'unit_price' => 17.00, 'supplier' => 'Suki Wholesale', 'description' => 'Instant noodles, beef flavor.'],
            ['name' => 'Pancit Canton Original', 'sku' => 'NOO-003', 'category' => 'Noodles', 'brand' => 'Lucky Me', 'quantity' => 36, 'reorder_level' => 12, 'unit_price' => 20.00, 'supplier' => 'Suki Wholesale', 'description' => 'Stir-fried instant noodles.'],
            ['name' => 'Chicharon Bulaklak 80g', 'sku' => 'SNK-001', 'category' => 'Snacks', 'brand' => 'Pinoy Snack', 'quantity' => 30, 'reorder_level' => 10, 'unit_price' => 55.00, 'supplier' => 'Mercury Snack House', 'description' => 'Crispy fried pork snack.'],
            ['name' => 'Piattos Cheese 85g', 'sku' => 'SNK-002', 'category' => 'Snacks', 'brand' => 'Piattos', 'quantity' => 6, 'reorder_level' => 10, 'unit_price' => 42.00, 'supplier' => 'Mercury Snack House', 'description' => 'Potato chips, cheese flavor.'],
            ['name' => 'Nova Biscuits 100g', 'sku' => 'SNK-003', 'category' => 'Snacks', 'brand' => 'Nova', 'quantity' => 40, 'reorder_level' => 15, 'unit_price' => 35.00, 'supplier' => 'Mercury Snack House', 'description' => 'Assorted cream biscuits.'],
            ['name' => 'Sardines in Tomato Sauce', 'sku' => 'CAN-001', 'category' => 'Canned Goods', 'brand' => '555', 'quantity' => 60, 'reorder_level' => 18, 'unit_price' => 32.00, 'supplier' => 'Suki Wholesale', 'description' => 'Canned sardines, 155g.'],
            ['name' => 'Corned Beef 340g', 'sku' => 'CAN-002', 'category' => 'Canned Goods', 'brand' => 'Purefoods', 'quantity' => 4, 'reorder_level' => 12, 'unit_price' => 95.00, 'supplier' => 'Suki Wholesale', 'description' => 'Canned corned beef.'],
            ['name' => 'Tuna in Oil 180g', 'sku' => 'CAN-003', 'category' => 'Canned Goods', 'brand' => 'Century', 'quantity' => 24, 'reorder_level' => 12, 'unit_price' => 48.00, 'supplier' => 'Suki Wholesale', 'description' => 'Canned tuna in oil.'],
            ['name' => 'Washing Powder 1kg', 'sku' => 'HOM-001', 'category' => 'Household', 'brand' => 'Surf', 'quantity' => 15, 'reorder_level' => 8, 'unit_price' => 120.00, 'supplier' => 'Aneja Distributors', 'description' => 'Laundry detergent powder.'],
            ['name' => 'Dishwashing Liquid 500ml', 'sku' => 'HOM-002', 'category' => 'Household', 'brand' => 'Joy', 'quantity' => 3, 'reorder_level' => 8, 'unit_price' => 75.00, 'supplier' => 'Aneja Distributors', 'description' => 'Lemon dishwashing liquid.'],
            ['name' => 'Toothpaste 150g', 'sku' => 'PER-001', 'category' => 'Personal Care', 'brand' => 'Colgate', 'quantity' => 28, 'reorder_level' => 10, 'unit_price' => 68.00, 'supplier' => 'Aneja Distributors', 'description' => 'Mint toothpaste.'],
            ['name' => 'Body Soap 125g', 'sku' => 'PER-002', 'category' => 'Personal Care', 'brand' => 'Dove', 'quantity' => 7, 'reorder_level' => 10, 'unit_price' => 32.00, 'supplier' => 'Aneja Distributors', 'description' => 'Scented bath soap.'],
            ['name' => 'Soy Sauce 1L', 'sku' => 'CON-001', 'category' => 'Condiments', 'brand' => 'Kikkoman', 'quantity' => 20, 'reorder_level' => 8, 'unit_price' => 55.00, 'supplier' => 'Suki Wholesale', 'description' => 'Dark soy sauce.'],
            ['name' => 'Cooking Oil 1L', 'sku' => 'CON-002', 'category' => 'Condiments', 'brand' => 'Great Value', 'quantity' => 12, 'reorder_level' => 6, 'unit_price' => 110.00, 'supplier' => 'Suki Wholesale', 'description' => 'Refined cooking oil.'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
