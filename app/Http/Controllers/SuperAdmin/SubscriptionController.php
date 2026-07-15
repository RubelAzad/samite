<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class SubscriptionController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $validated = $request->validate([
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            'starts_at' => 'required|date',
            'amount' => 'required|numeric|min:0',
            'payment_reference' => 'nullable|string|max:255',
        ]);

        $package = SubscriptionPackage::findOrFail($validated['subscription_package_id']);
        $startsAt = Carbon::parse($validated['starts_at']);
        $endsAt = $package->billing_cycle === 'yearly'
            ? $startsAt->copy()->addYear()
            : $startsAt->copy()->addMonth();

        Subscription::create([
            'company_id' => $company->id,
            'subscription_package_id' => $package->id,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'amount' => $validated['amount'],
            'payment_method' => 'manual',
            'payment_reference' => $validated['payment_reference'] ?? null,
            'activated_by' => auth()->id(),
        ]);

        $company->update([
            'subscription_package_id' => $package->id,
            'subscription_ends_at' => $endsAt,
            'status' => 'active',
        ]);

        return back()->with('success', "Subscription activated through {$endsAt->toFormattedDateString()}.");
    }
}
