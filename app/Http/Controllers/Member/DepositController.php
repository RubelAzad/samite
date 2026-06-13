<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Deposit;
use Illuminate\Http\Request;

class DepositController extends Controller
{
    public function index(Request $request)
    {
        $member = auth()->user()->member;

        $deposits = $member->deposits()
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('member.deposits.index', compact('deposits'));
    }

    public function create()
    {
        return view('member.deposits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount'         => 'required|numeric|min:1',
            'date'           => 'required|date|before_or_equal:today',
            'payment_method' => 'required|in:cash,bkash,nagad,bank',
            'notes'          => 'nullable|string|max:500',
            'attachment'     => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
        ]);

        $member = auth()->user()->member;

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('deposits', 'public');
        }

        $deposit = $member->deposits()->create([
            'amount'         => $request->amount,
            'date'           => $request->date,
            'payment_method' => $request->payment_method,
            'notes'          => $request->notes,
            'attachment'     => $attachmentPath,
            'status'         => 'pending',
        ]);

        AuditLog::record('create', 'deposit', $deposit->id, null, $deposit->toArray(), "Member submitted deposit request of {$deposit->amount}");

        return redirect()->route('member.deposits.index')->with('success', 'Deposit request submitted. Awaiting admin approval.');
    }
}
