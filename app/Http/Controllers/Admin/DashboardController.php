<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Expense;
use App\Models\LedgerEntry;
use App\Models\Member;

class DashboardController extends Controller
{
    public function index()
    {
        $totalMembers    = Member::count();
        $activeMembers   = Member::where('status', 'active')->count();
        $totalDeposits   = Deposit::where('status', 'approved')->sum('amount');
        $totalExpenses   = Expense::where('status', 'approved')->sum('amount');
        $netBalance      = LedgerEntry::currentBalance();
        $pendingDeposits = Deposit::where('status', 'pending')->count();

        $recentDeposits = Deposit::with('member.user')
            ->latest()
            ->take(5)
            ->get();

        $recentExpenses = Expense::with('createdBy')
            ->latest()
            ->take(5)
            ->get();

        // Monthly income vs expense for current year
        $months = [];
        for ($m = 1; $m <= 12; $m++) {
            $year = now()->year;
            $months[] = [
                'month'   => date('M', mktime(0, 0, 0, $m, 1)),
                'income'  => Deposit::where('status', 'approved')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $m)
                    ->sum('amount'),
                'expense' => Expense::where('status', 'approved')
                    ->whereYear('date', $year)
                    ->whereMonth('date', $m)
                    ->sum('amount'),
            ];
        }

        return view('admin.dashboard', compact(
            'totalMembers', 'activeMembers', 'totalDeposits',
            'totalExpenses', 'netBalance', 'pendingDeposits',
            'recentDeposits', 'recentExpenses', 'months'
        ));
    }
}
