<?php

namespace Database\Seeders;

use App\Models\Returns;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReturnsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Returns::create([
            'order_item_id' => 1,
            'quantity' => 5,
            'reason' => 'Damaged product'
        ]);

        Returns::create([
            'order_item_id' => 2,
            'quantity' => 10,
            'reason' => 'Incorrect order'
        ]);

    }
}
