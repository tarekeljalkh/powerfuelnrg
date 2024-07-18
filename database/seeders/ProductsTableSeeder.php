<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Product::create([
            'name' => 'Diesel',
            'description' => 'High-quality diesel fuel',
            'price' => 1.50
        ]);

        Product::create([
            'name' => 'Petrol',
            'description' => 'Premium unleaded petrol',
            'price' => 1.80
        ]);

    }
}
