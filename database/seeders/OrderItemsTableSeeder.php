<?php

namespace Database\Seeders;

use App\Models\OrderItem;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrderItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OrderItem::create([
            'order_id' => 1,
            'product_id' => 1,
            'quantity' => 50,
            'price' => 1.50
        ]);

        OrderItem::create([
            'order_id' => 2,
            'product_id' => 2,
            'quantity' => 100,
            'price' => 2.00
        ]);

    }
}
