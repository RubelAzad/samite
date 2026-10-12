<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Deposit;
use App\Models\LedgerEntry;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $query = Deposit::with('member.user')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->member_id, fn ($q) => $q->where('member_id', $request->member_id))
            ->when($request->from, fn ($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->to, fn ($q) => $q->whereDate('date', '<=', $request->to));

        $deposits = $query->latest()->paginate(20)->withQueryString();
        $members = Member::with('user')->where('status', 'active')->get();

        return view('admin.deposits.index', compact('deposits', 'members'));
    }

    public function show(Deposit $deposit)
    {
        $deposit->load('member.user', 'approvedBy');

        return view('admin.deposits.show', compact('deposit'));
    }

    public function receipt(Deposit $deposit)
    {
        $deposit->load('member.user', 'approvedBy', 'bankAccount');

        return view('admin.deposits.receipt', compact('deposit'));
    }

    public function approve(Request $request, Deposit $deposit)
    {
        if (! $deposit->isPending()) {
            return back()->with('error', 'Deposit is not pending.');
        }

        DB::transaction(function () use ($deposit) {
            $deposit->update([
                'status' => 'approved',
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

        if (! $deposit->isPending()) {
            return back()->with('error', 'Deposit is not pending.');
        }

        $deposit->update([
            'status' => 'rejected',
            'rejection_reason' => $request->rejection_reason,
            'approved_by' => auth()->id(),
            'approved_at' => now(),
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
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,bkash,nagad,bank',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('deposits', 'public');
        }

        DB::transaction(function () use ($request, $attachmentPath) {
            $deposit = Deposit::create([
                'member_id' => $request->member_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'attachment' => $attachmentPath,
                'status' => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);

            LedgerEntry::recordDeposit($deposit);

            AuditLog::record('create', 'deposit', $deposit->id, null, $deposit->toArray(), "Admin created & approved deposit #{$deposit->id}");
        });

        return redirect()->route('admin.deposits.index')->with('success', 'Deposit recorded and approved.');
    }

    public function edit(Deposit $deposit)
    {
        $members = Member::with('user')->where('status', 'active')->get();

        return view('admin.deposits.edit', compact('deposit', 'members'));
    }

    public function update(Request $request, Deposit $deposit)
    {
        $request->validate([
            'member_id' => 'required|exists:members,id',
            'amount' => 'required|numeric|min:1',
            'date' => 'required|date',
            'payment_method' => 'required|in:cash,bkash,nagad,bank',
            'notes' => 'nullable|string|max:500',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
            'remove_attachment' => 'nullable|boolean',
        ]);

        $oldValues = $deposit->toArray();

        DB::transaction(function () use ($request, $deposit, $oldValues) {
            $attachmentPath = $deposit->attachment;
            if ($request->hasFile('attachment')) {
                if ($deposit->attachment) {
                    Storage::disk('public')->delete($deposit->attachment);
                }
                $attachmentPath = $request->file('attachment')->store('deposits', 'public');
            } elseif ($request->boolean('remove_attachment')) {
                if ($deposit->attachment) {
                    Storage::disk('public')->delete($deposit->attachment);
                }
                $attachmentPath = null;
            }

            $deposit->update([
                'member_id' => $request->member_id,
                'amount' => $request->amount,
                'date' => $request->date,
                'payment_method' => $request->payment_method,
                'notes' => $request->notes,
                'attachment' => $attachmentPath,
            ]);

            if ($deposit->isApproved()) {
                LedgerEntry::where('reference_type', 'deposit')
                    ->where('reference_id', $deposit->id)
                    ->delete();
                LedgerEntry::recordDeposit($deposit->fresh());
            }

            AuditLog::record('update', 'deposit', $deposit->id, $oldValues, $deposit->toArray(), "Updated deposit #{$deposit->id}");
        });

        return redirect()->route('admin.deposits.index')->with('success', 'Deposit updated successfully.');
    }

    public function destroy(Deposit $deposit)
    {
        if ($deposit->isApproved()) {
            return back()->with('error', 'Cannot delete approved deposit. Reject it first.');
        }

        $oldValues = $deposit->toArray();

        DB::transaction(function () use ($deposit, $oldValues) {
            if ($deposit->attachment) {
                Storage::disk('public')->delete($deposit->attachment);
            }
            $deposit->delete();
            AuditLog::record('delete', 'deposit', $deposit->id, $oldValues, null, "Deleted deposit #{$deposit->id}");
        });

        return redirect()->route('admin.deposits.index')->with('success', 'Deposit deleted successfully.');
    }
}
