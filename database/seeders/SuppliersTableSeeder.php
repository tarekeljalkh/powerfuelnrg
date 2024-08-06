<?php

namespace Database\Seeders;

use App\Models\Supplier;
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
            'phone' => '111222333',
        ]);

        Supplier::create([
            'name' => 'Supplier 2',
            'email' => 'supplier2@example.com',
            'phone' => '444555666',
        ]);

        Supplier::create([
            'name' => 'Supplier 3',
            'email' => 'supplier3@example.com',
            'phone' => '777888999',
        ]);

        Supplier::create([
            'name' => 'Supplier 4',
            'email' => 'supplier4@example.com',
            'phone' => '123456789',
        ]);

        Supplier::create([
            'name' => 'Supplier 5',
            'email' => 'supplier5@example.com',
            'phone' => '987654321',
        ]);
    }
}
