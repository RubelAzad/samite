<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BankAccount;
use App\Models\LedgerEntry;
use App\Models\Member;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function index(Request $request)
    {
        $query = Withdrawal::with('member.user', 'bankAccount')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->purpose, fn ($q) => $q->where('purpose', $request->purpose))
            ->when($request->member_id, fn ($q) => $q->where('member_id', $request->member_id))
            ->when($request->bank_account_id, fn ($q) => $q->where('bank_account_id', $request->bank_account_id))
            ->when($request->from, fn ($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->to, fn ($q) => $q->whereDate('date', '<=', $request->to));

        $withdrawals = $query->latest()->paginate(20)->withQueryString();
        $members = Member::with('user')->where('status', 'active')->get();
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('admin.withdrawals.index', compact('withdrawals', 'members', 'bankAccounts'));
    }

    public function show(Withdrawal $withdrawal)
    {
        $withdrawal->load('member.user', 'approvedBy', 'bankAccount');

        return view('admin.withdrawals.show', compact('withdrawal'));
    }

    public function approve(Request $request, Withdrawal $withdrawal)
    {
        if (! $withdrawal->isPending()) {
            return back()->with('error', 'Withdrawal is not pending.');
        }

        DB::transaction(function () use ($withdrawal) {
            $withdrawal->update([
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            LedgerEntry::recordWithdrawal($withdrawal);

            AuditLog::record('approve', 'withdrawal', $withdrawal->id, ['status' => 'pending'], ['status' => 'approved'], "Approved withdrawal #{$withdrawal->id} of {$withdrawal->amount} for member #{$withdrawal->member_id}");
        });

        return back()->with('success', 'Withdrawal approved and ledger updated.');
    }

    public function reject(Request $request, Withdrawal $withdrawal)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (! $withdrawal->isPending()) {
            return back()->with('error', 'Withdrawal is not pending.');
        }

        $withdrawal->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        AuditLog::record('reject', 'withdrawal', $withdrawal->id, ['status' => 'pending'], ['status' => 'rejected'], "Rejected withdrawal #{$withdrawal->id}");

        return back()->with('success', 'Withdrawal rejected.');
    }

    public function create()
    {
        $members = Member::with('user')->where('status', 'active')->get();
        $bankAccounts = BankAccount::where('is_active', true)->get();

        return view('admin.withdrawals.create', compact('members', 'bankAccounts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'bank_account_id' => 'nullable|exists:bank_accounts,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,bkash,nagad,bank',
            'purpose' => 'nullable|in:land_purchase,medical,business,education,emergency,other',
            'purpose_details' => 'nullable|string|max:500',
            'notes' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $withdrawal = Withdrawal::create([
                'member_id' => $request->member_id,
                'bank_account_id' => $request->bank_account_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'purpose' => $request->purpose,
                'purpose_details' => $request->purpose_details,
                'notes' => $request->notes,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            LedgerEntry::recordWithdrawal($withdrawal);

            AuditLog::record('create', 'withdrawal', $withdrawal->id, null, $withdrawal->toArray(), "Admin created & approved withdrawal #{$withdrawal->id}");
        });

        return redirect()->route('admin.withdrawals.index')->with('success', 'Withdrawal recorded and approved.');
    }
}
