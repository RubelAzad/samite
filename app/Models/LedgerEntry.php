<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LedgerEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'member_id', 'reference_type', 'reference_id',
        'credit_amount', 'debit_amount', 'balance_after', 'date', 'description',
    ];

    protected $casts = [
        'date' => 'date',
        'credit_amount' => 'decimal:2',
        'debit_amount'  => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public static function currentBalance(): float
    {
        $last = static::latest('id')->first();
        return $last ? (float) $last->balance_after : 0.0;
    }

    public static function recordDeposit(Deposit $deposit): static
    {
        $balance = static::currentBalance() + $deposit->amount;

        return static::create([
            'type'           => 'deposit',
            'member_id'      => $deposit->member_id,
            'reference_type' => 'deposit',
            'reference_id'   => $deposit->id,
            'credit_amount'  => $deposit->amount,
            'debit_amount'   => 0,
            'balance_after'  => $balance,
            'date'           => $deposit->date,
            'description'    => "Deposit by member #{$deposit->member_id}",
        ]);
    }

    public static function recordExpense(Expense $expense): static
    {
        $balance = static::currentBalance() - $expense->amount;

        return static::create([
            'type'           => 'expense',
            'member_id'      => null,
            'reference_type' => 'expense',
            'reference_id'   => $expense->id,
            'credit_amount'  => 0,
            'debit_amount'   => $expense->amount,
            'balance_after'  => $balance,
            'date'           => $expense->date,
            'description'    => "Expense: {$expense->title}",
        ]);
    }
}
