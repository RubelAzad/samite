<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'member_code', 'phone', 'address',
        'join_date', 'deposit_plan', 'status',
    ];

    protected $casts = [
        'join_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function totalApprovedDeposits(): float
    {
        return $this->deposits()->where('status', 'approved')->sum('amount');
    }

    public function totalPendingDeposits(): float
    {
        return $this->deposits()->where('status', 'pending')->sum('amount');
    }
}
