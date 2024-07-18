<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inventory::create([
            'product_id' => 1,
            'quantity' => 1000
        ]);

        Inventory::create([
            'product_id' => 2,
            'quantity' => 800
        ]);

    }
}
