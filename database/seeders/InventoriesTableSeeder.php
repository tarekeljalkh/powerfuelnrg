<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inventory::create([
            'supplier_id' => 1, // Assuming supplier with ID 1 exists
            'fuel_type' => 'Diesel',
            'quantity' => 1000.00
        ]);

        Inventory::create([
            'supplier_id' => 2, // Assuming supplier with ID 2 exists
            'fuel_type' => 'Petrol',
            'quantity' => 800.00
        ]);

        Inventory::create([
            'supplier_id' => 3, // Assuming supplier with ID 3 exists
            'fuel_type' => 'Kerosene',
            'quantity' => 500.00
        ]);

        Inventory::create([
            'supplier_id' => 4, // Assuming supplier with ID 4 exists
            'fuel_type' => 'LPG',
            'quantity' => 1200.00
        ]);

        Inventory::create([
            'supplier_id' => 5, // Assuming supplier with ID 5 exists
            'fuel_type' => 'Biodiesel',
            'quantity' => 900.00
        ]);
    }
}
