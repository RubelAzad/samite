<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'company_id', 'subscription_package_id', 'starts_at', 'ends_at',
        'amount', 'payment_method', 'payment_reference', 'activated_by',
    ];

    protected $casts = [
        'starts_at' => 'date',
        'ends_at' => 'date',
        'amount' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function package()
    {
        return $this->belongsTo(SubscriptionPackage::class, 'subscription_package_id');
    }

    public function activatedBy()
    {
        return $this->belongsTo(User::class, 'activated_by');
    }
}
