<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'bank_name', 'account_number', 'branch', 'type',
        'opening_balance', 'current_balance', 'is_active', 'notes',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'current_balance' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class, 'bank_account_id');
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class, 'bank_account_id');
    }

    public function updateBalance(): void
    {
        $this->current_balance = $this->ledgerEntries()
            ->where('type', 'withdrawal')
            ->sum('debit_amount') * -1
            + $this->ledgerEntries()
                ->where('type', 'deposit')
                ->sum('credit_amount')
            + $this->opening_balance;
        $this->save();
    }
}
