<?php

namespace Database\Seeders;

use App\Models\Expense;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExpensesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Expense::create([
            'description' => 'Office Supplies',
            'amount' => 150.00,
            'expense_date' => now()
        ]);

        Expense::create([
            'description' => 'Fuel Purchase',
            'amount' => 5000.00,
            'expense_date' => now()
        ]);

    }
}
