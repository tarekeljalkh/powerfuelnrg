<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SuppliersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Supplier::create([
            'name' => 'Supplier 1',
            'email' => 'supplier1@example.com',
            'phone' => '111222333'
        ]);

        Supplier::create([
            'name' => 'Supplier 2',
            'email' => 'supplier2@example.com',
            'phone' => '444555666'
        ]);

    }
}
