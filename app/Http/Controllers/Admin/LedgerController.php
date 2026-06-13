<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\LedgerEntry;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index(Request $request)
    {
        $query = LedgerEntry::with('member.user')
            ->when($request->type, fn($q) => $q->where('type', $request->type))
            ->when($request->from, fn($q) => $q->whereDate('date', '>=', $request->from))
            ->when($request->to, fn($q) => $q->whereDate('date', '<=', $request->to));

        $entries     = $query->latest('id')->paginate(25)->withQueryString();
        $totalCredit = LedgerEntry::sum('credit_amount');
        $totalDebit  = LedgerEntry::sum('debit_amount');
        $balance     = LedgerEntry::currentBalance();

        return view('admin.ledger.index', compact('entries', 'totalCredit', 'totalDebit', 'balance'));
    }
}
