<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /** Change own password (no old password required for admin) */
    public function edit()
    {
        return view('admin.password.edit', ['target' => auth()->user(), 'self' => true]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user = auth()->user();
        $user->update(['password' => Hash::make($request->password)]);

        AuditLog::record('password_change', 'user', $user->id, null, null, "Admin changed own password");

        return back()->with('success', 'Password updated successfully.');
    }

    /** Admin resets a member's password */
    public function editMember(User $user)
    {
        abort_if($user->isAdmin(), 403);
        return view('admin.password.edit', ['target' => $user, 'self' => false]);
    }

    public function updateMember(Request $request, User $user)
    {
        abort_if($user->isAdmin(), 403);

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        $user->update(['password' => Hash::make($request->password)]);

        AuditLog::record('password_reset', 'user', $user->id, null, null, "Admin reset password for {$user->name}");

        return redirect()->route('admin.members.show', $user->member)->with('success', "Password reset for {$user->name}.");
    }
}
