<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::with('user')
            ->when($request->search, fn($q) => $q->whereHas('user', fn($u) => $u->where('name', 'like', "%{$request->search}%")))
            ->when($request->status, fn($q) => $q->where('status', $request->status));

        $members = $query->latest()->paginate(15)->withQueryString();

        return view('admin.members.index', compact('members'));
    }

    public function create()
    {
        return view('admin.members.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|min:8|confirmed',
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'join_date'    => 'required|date',
            'deposit_plan' => 'required|in:daily,monthly',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'     => $request->name,
                'email'    => $request->email,
                'password' => Hash::make($request->password),
                'role'     => 'member',
                'status'   => 'active',
            ]);

            $member = Member::create([
                'user_id'      => $user->id,
                'member_code'  => $this->generateMemberCode(),
                'phone'        => $request->phone,
                'address'      => $request->address,
                'join_date'    => $request->join_date,
                'deposit_plan' => $request->deposit_plan,
                'status'       => 'active',
            ]);

            AuditLog::record('create', 'member', $member->id, null, $member->toArray(), "Created member {$user->name}");
        });

        return redirect()->route('admin.members.index')->with('success', 'Member created successfully.');
    }

    public function show(Member $member)
    {
        $member->load('user', 'deposits', 'ledgerEntries');
        return view('admin.members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        $member->load('user');
        return view('admin.members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $member->load('user');

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $member->user_id,
            'phone'        => 'nullable|string|max:20',
            'address'      => 'nullable|string',
            'join_date'    => 'required|date',
            'deposit_plan' => 'required|in:daily,monthly',
            'status'       => 'required|in:active,inactive',
        ]);

        $old = $member->toArray();

        DB::transaction(function () use ($request, $member) {
            $member->user->update([
                'name'   => $request->name,
                'email'  => $request->email,
                'status' => $request->status,
            ]);

            $member->update([
                'phone'        => $request->phone,
                'address'      => $request->address,
                'join_date'    => $request->join_date,
                'deposit_plan' => $request->deposit_plan,
                'status'       => $request->status,
            ]);
        });

        AuditLog::record('update', 'member', $member->id, $old, $member->fresh()->toArray(), "Updated member {$member->user->name}");

        return redirect()->route('admin.members.index')->with('success', 'Member updated successfully.');
    }

    public function toggleStatus(Member $member)
    {
        $newStatus = $member->status === 'active' ? 'inactive' : 'active';
        $member->update(['status' => $newStatus]);
        $member->user->update(['status' => $newStatus]);

        AuditLog::record('toggle_status', 'member', $member->id, null, ['status' => $newStatus], "Member status set to {$newStatus}");

        return back()->with('success', "Member status updated to {$newStatus}.");
    }

    private function generateMemberCode(): string
    {
        $last = Member::latest('id')->first();
        $next = $last ? ($last->id + 1) : 1;
        return 'SMT-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
