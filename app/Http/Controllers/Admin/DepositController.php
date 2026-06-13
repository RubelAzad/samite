<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Deposit;
use App\Models\LedgerEntry;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $query = Deposit::with('member.user')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->member_id, fn($q) => $q->where('member_id', $request->member_id))
            ->when($request->from, fn($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('date', '<=', $request->to));

        $deposits = $query->latest()->paginate(20)->withQueryString();
        $members  = Member::with('user')->where('status', 'active')->get();

        return view('admin.deposits.index', compact('deposits', 'members'));
    }

    public function show(Deposit $deposit)
    {
        $deposit->load('member.user', 'approvedBy');
        return view('admin.deposits.show', compact('deposit'));
    }

    public function approve(Request $request, Deposit $deposit)
    {
        if (!$deposit->isPending()) {
            return back()->with('error', 'Deposit is not pending.');
        }

        DB::transaction(function () use ($deposit) {
            $deposit->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            LedgerEntry::recordDeposit($deposit);

            AuditLog::record('approve', 'deposit', $deposit->id, ['status' => 'pending'], ['status' => 'approved'], "Approved deposit #{$deposit->id} of {$deposit->amount} for member #{$deposit->member_id}");
        });

        return back()->with('success', 'Deposit approved and ledger updated.');
    }

    public function reject(Request $request, Deposit $deposit)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500',
        ]);

        if (!$deposit->isPending()) {
            return back()->with('error', 'Deposit is not pending.');
        }

        $deposit->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by'      => auth()->id(),
            'approved_at'      => now(),
        ]);

        AuditLog::record('reject', 'deposit', $deposit->id, ['status' => 'pending'], ['status' => 'rejected'], "Rejected deposit #{$deposit->id}");

        return back()->with('success', 'Deposit rejected.');
    }

    public function create()
    {
        $members = Member::with('user')->where('status', 'active')->get();
        return view('admin.deposits.create', compact('members'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'member_id'      => 'required|exists:members,id',
            'amount'         => 'required|numeric|min:1',
            'date'           => 'required|date',
            'payment_method' => 'required|in:cash,bkash,nagad,bank',
            'notes'          => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request) {
            $deposit = Deposit::create([
                'member_id'      => $request->member_id,
                'amount'         => $request->amount,
                'date'           => $request->date,
                'payment_method' => $request->payment_method,
                'notes'          => $request->notes,
                'status'         => 'approved',
                'approved_by'    => auth()->id(),
                'approved_at'    => now(),
            ]);

            LedgerEntry::recordDeposit($deposit);

            AuditLog::record('create', 'deposit', $deposit->id, null, $deposit->toArray(), "Admin created & approved deposit #{$deposit->id}");
        });

        return redirect()->route('admin.deposits.index')->with('success', 'Deposit recorded and approved.');
    }
}
