<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPackage;
use Illuminate\Http\Request;

class PackageController extends Controller
{
    public const FEATURES = [
        'deposits' => 'Deposits',
        'expenses' => 'Expenses',
        'ledger' => 'Ledger',
        'reports' => 'Reports',
        'audit_log' => 'Audit Log',
    ];

    public function index()
    {
        $packages = SubscriptionPackage::withCount('companies')->latest()->get();

        return view('super-admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('super-admin.packages.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validated($request);

        SubscriptionPackage::create($validated);

        return redirect()->route('super-admin.packages.index')->with('success', 'Package created successfully.');
    }

    public function edit(SubscriptionPackage $package)
    {
        return view('super-admin.packages.edit', compact('package'));
    }

    public function update(Request $request, SubscriptionPackage $package)
    {
        $validated = $this->validated($request);

        $package->update($validated);

        return redirect()->route('super-admin.packages.index')->with('success', 'Package updated successfully.');
    }

    public function toggleStatus(SubscriptionPackage $package)
    {
        $package->update([
            'status' => $package->isActive() ? 'inactive' : 'active',
        ]);

        return back()->with('success', 'Package status updated.');
    }

    private function validated(Request $request): array
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'billing_cycle' => 'required|in:monthly,yearly',
            'member_limit' => 'required|integer|min:1',
            'features' => 'nullable|array',
            'features.*' => 'in:'.implode(',', array_keys(self::FEATURES)),
        ]);

        $validated['features'] = $validated['features'] ?? [];

        return $validated;
    }
}
