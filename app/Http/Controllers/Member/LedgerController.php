<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Models\LedgerEntry;

class LedgerController extends Controller
{
    public function index()
    {
        $member = auth()->user()->member;

        $entries = LedgerEntry::where('member_id', $member->id)
            ->latest('id')
            ->paginate(20);

        $totalDeposited = $member->deposits()->where('status', 'approved')->sum('amount');

        return view('member.ledger.index', compact('entries', 'totalDeposited', 'member'));
    }
}
