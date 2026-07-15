<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Subscription;
use App\Models\SubscriptionPackage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->search;
        $status = $request->status; // active | expiring | expired

        $companies = Company::withCount(['users', 'members'])
            ->with('subscriptionPackage')
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            ->when($status === 'expired', fn ($q) => $q->where(fn ($q2) => $q2
                ->where('status', 'inactive')
                ->orWhere('subscription_ends_at', '<', now())
            ))
            ->when($status === 'expiring', fn ($q) => $q
                ->where('status', 'active')
                ->whereBetween('subscription_ends_at', [now(), now()->addDays(7)])
            )
            ->when($status === 'active', fn ($q) => $q
                ->where('status', 'active')
                ->where(fn ($q2) => $q2
                    ->whereNull('subscription_ends_at')
                    ->orWhere('subscription_ends_at', '>', now()->addDays(7))
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('super-admin.companies.index', compact('companies', 'search', 'status'));
    }

    public function create()
    {
        $packages = SubscriptionPackage::where('status', 'active')->get();

        return view('super-admin.companies.create', compact('packages'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'admin_name' => 'required|string|max:255',
            'admin_email' => 'required|email|max:255|unique:users,email',
            'admin_password' => 'required|string|min:8',
            'subscription_package_id' => 'required|exists:subscription_packages,id',
            'amount' => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($validated) {
            $package = SubscriptionPackage::findOrFail($validated['subscription_package_id']);
            $startsAt = Carbon::today();
            $endsAt = $package->billing_cycle === 'yearly'
                ? $startsAt->copy()->addYear()
                : $startsAt->copy()->addMonth();

            $company = Company::create([
                'name' => $validated['name'],
                'slug' => Str::slug($validated['name']).'-'.Str::random(5),
                'phone' => $validated['phone'] ?? null,
                'email' => $validated['email'] ?? null,
                'address' => $validated['address'] ?? null,
                'status' => 'active',
                'subscription_package_id' => $package->id,
                'subscription_ends_at' => $endsAt,
            ]);

            User::create([
                'name' => $validated['admin_name'],
                'email' => $validated['admin_email'],
                'password' => Hash::make($validated['admin_password']),
                'role' => 'admin',
                'status' => 'active',
                'company_id' => $company->id,
            ]);

            Subscription::create([
                'company_id' => $company->id,
                'subscription_package_id' => $package->id,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'amount' => $validated['amount'],
                'payment_method' => 'manual',
                'activated_by' => auth()->id(),
            ]);
        });

        return redirect()->route('super-admin.companies.index')->with('success', 'Company created successfully.');
    }

    public function edit(Company $company)
    {
        $packages = SubscriptionPackage::where('status', 'active')->get();
        $subscriptions = $company->subscriptions()->with('package')->latest('id')->get();

        return view('super-admin.companies.edit', compact('company', 'packages', 'subscriptions'));
    }

    public function update(Request $request, Company $company)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
        ]);

        $company->update($validated);

        return redirect()->route('super-admin.companies.index')->with('success', 'Company updated successfully.');
    }

    public function toggleStatus(Company $company)
    {
        $company->update([
            'status' => $company->isActive() ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Company status updated.');
    }
}
