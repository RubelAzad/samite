<?php

namespace Database\Seeders;

use App\Models\BankAccount;
use Illuminate\Database\Seeder;

class BankAccountSeeder extends Seeder
{
    public function run(): void
    {
        BankAccount::create([
            'name' => 'Main Operating Account',
            'bank_name' => 'Dutch Bangla Bank Ltd',
            'account_number' => '1234567890',
            'branch' => 'Dhanmondi Branch',
            'type' => 'current',
            'opening_balance' => 5000000,
            'current_balance' => 5000000,
            'is_active' => true,
            'notes' => 'Primary bank account for society operations',
        ]);

        BankAccount::create([
            'name' => 'Savings Account',
            'bank_name' => 'BRAC Bank Ltd',
            'account_number' => '0987654321',
            'branch' => 'Gulshan Branch',
            'type' => 'savings',
            'opening_balance' => 1000000,
            'current_balance' => 1000000,
            'is_active' => true,
            'notes' => 'Savings account for reserve funds',
        ]);

        BankAccount::create([
            'name' => 'Islami Bank Account',
            'bank_name' => 'Islami Bank Bangladesh Ltd',
            'account_number' => '1122334455',
            'branch' => 'Mohammadpur Branch',
            'type' => 'current',
            'opening_balance' => 2000000,
            'current_balance' => 2000000,
            'is_active' => true,
            'notes' => 'Shariah-compliant account',
        ]);
    }
}
