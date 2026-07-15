<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'phone', 'email', 'address', 'status',
        'subscription_package_id', 'subscription_ends_at', 'logo', 'icon',
    ];

    protected $casts = [
        'subscription_ends_at' => 'date',
    ];

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isSubscriptionExpired(): bool
    {
        return $this->subscription_ends_at !== null && $this->subscription_ends_at->isPast();
    }

    public function isSubscriptionExpiringSoon(int $days = 7): bool
    {
        return $this->subscription_ends_at !== null
            && ! $this->isSubscriptionExpired()
            && now()->diffInDays($this->subscription_ends_at, false) <= $days;
    }

    /** 'expired'|'expiring'|'active' — drives the green/yellow/red status badge everywhere. */
    public function subscriptionStatus(): string
    {
        if (! $this->isActive() || $this->isSubscriptionExpired()) {
            return 'expired';
        }

        if ($this->isSubscriptionExpiringSoon()) {
            return 'expiring';
        }

        return 'active';
    }

    public function memberLimitReached(): bool
    {
        return $this->subscriptionPackage !== null
            && $this->members()->count() >= $this->subscriptionPackage->member_limit;
    }

    public function hasFeature(string $key): bool
    {
        return $this->subscriptionPackage?->hasFeature($key) ?? true;
    }

    public function subscriptionPackage()
    {
        return $this->belongsTo(SubscriptionPackage::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function deposits()
    {
        return $this->hasMany(Deposit::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function ledgerEntries()
    {
        return $this->hasMany(LedgerEntry::class);
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }
}
