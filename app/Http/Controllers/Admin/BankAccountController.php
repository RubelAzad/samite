<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $query = BankAccount::query()
            ->when($request->status, fn ($q) => $q->where('is_active', $request->status === 'active'));

        $bankAccounts = $query->latest()->paginate(20)->withQueryString();

        return view('admin.bank-accounts.index', compact('bankAccounts'));
    }

    public function create()
    {
        return view('admin.bank-accounts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'branch' => 'nullable|string|max:100',
            'type' => 'required|in:savings,current,fd',
            'opening_balance' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:500',
        ]);

        BankAccount::create([
            'name' => $request->name,
            'bank_name' => $request->bank_name,
            'account_number' => $request->account_number,
            'branch' => $request->branch,
            'type' => $request->type,
            'opening_balance' => $request->opening_balance ?? 0,
            'current_balance' => $request->opening_balance ?? 0,
            'is_active' => true,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.bank-accounts.index')->with('success', 'Bank account created successfully.');
    }

    public function show(BankAccount $bankAccount)
    {
        $bankAccount->load(['withdrawals.member.user', 'ledgerEntries']);

        return view('admin.bank-accounts.show', compact('bankAccount'));
    }

    public function edit(BankAccount $bankAccount)
    {
        return view('admin.bank-accounts.edit', compact('bankAccount'));
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'bank_name' => 'required|string|max:100',
            'account_number' => 'required|string|max:50',
            'branch' => 'nullable|string|max:100',
            'type' => 'required|in:savings,current,fd',
            'opening_balance' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
            'notes' => 'nullable|string|max:500',
        ]);

        $bankAccount->update($request->only([
            'name', 'bank_name', 'account_number', 'branch', 'type',
            'opening_balance', 'is_active', 'notes',
        ]));

        return redirect()->route('admin.bank-accounts.index')->with('success', 'Bank account updated successfully.');
    }

    public function destroy(BankAccount $bankAccount)
    {
        if ($bankAccount->withdrawals()->exists() || $bankAccount->ledgerEntries()->exists()) {
            return back()->with('error', 'Cannot delete bank account with existing transactions.');
        }

        $bankAccount->delete();

        return redirect()->route('admin.bank-accounts.index')->with('success', 'Bank account deleted successfully.');
    }
}
