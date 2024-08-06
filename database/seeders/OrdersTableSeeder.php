<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'client_id' => 1, // Assuming client with ID 1 exists
            'inventory_id' => 1, // Assuming inventory with ID 1 exists
            'quantity' => 50.00, // Quantity of fuel ordered
            'price' => 1.50, // Price per unit
            'order_date' => now(),
            'status' => 'paid'
        ]);

        Order::create([
            'client_id' => 2, // Assuming client with ID 2 exists
            'inventory_id' => 2, // Assuming inventory with ID 2 exists
            'quantity' => 100.00, // Quantity of fuel ordered
            'price' => 2.00, // Price per unit
            'order_date' => now(),
            'status' => 'paid'
        ]);

        Order::create([
            'client_id' => 3, // Assuming client with ID 3 exists
            'inventory_id' => 3, // Assuming inventory with ID 3 exists
            'quantity' => 30.00, // Quantity of fuel ordered
            'price' => 2.50, // Price per unit
            'order_date' => now(),
            'status' => 'paid'
        ]);

        Order::create([
            'client_id' => 4, // Assuming client with ID 4 exists
            'inventory_id' => 4, // Assuming inventory with ID 4 exists
            'quantity' => 40.00, // Quantity of fuel ordered
            'price' => 3.00, // Price per unit
            'order_date' => now(),
            'status' => 'paid'
        ]);

        Order::create([
            'client_id' => 5, // Assuming client with ID 5 exists
            'inventory_id' => 5, // Assuming inventory with ID 5 exists
            'quantity' => 60.00, // Quantity of fuel ordered
            'price' => 1.25, // Price per unit
            'order_date' => now(),
            'status' => 'paid'
        ]);
    }
}
