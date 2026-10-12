<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'member_code', 'phone', 'address', 'photo',
        'join_date', 'deposit_plan', 'status',
    ];

    public function getPhotoUrlAttribute(): ?string
    {
        return $this->photo ? asset('storage/'.$this->photo) : null;
    }

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

    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
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

    public function totalApprovedWithdrawals(): float
    {
        return $this->withdrawals()->where('status', 'approved')->sum('amount');
    }

    public function totalPendingWithdrawals(): float
    {
        return $this->withdrawals()->where('status', 'pending')->sum('amount');
    }
}
