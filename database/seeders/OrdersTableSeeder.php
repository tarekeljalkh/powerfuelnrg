<?php

namespace Database\Seeders;

use App\Models\Order;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Order::create([
            'client_id' => 1,
            'total' => 100.00,
            'order_date' => now(),
            'status' => 'completed'
        ]);

        Order::create([
            'client_id' => 2,
            'total' => 200.00,
            'order_date' => now(),
            'status' => 'pending'
        ]);

    }
}
