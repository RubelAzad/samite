<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\Deposit;

class DashboardController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        if (!$member) {
            abort(404, 'Member profile not found.');
        }

        $totalApproved = $member->deposits()->where('status', 'approved')->sum('amount');
        $totalPending  = $member->deposits()->where('status', 'pending')->count();
        $recentDeposits = $member->deposits()->latest()->take(5)->get();

        return view('member.dashboard', compact('member', 'totalApproved', 'totalPending', 'recentDeposits'));
    }
}
