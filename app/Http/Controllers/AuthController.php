<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return $this->redirectByRole();
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required',
        ]);

        $login    = trim($request->login);
        $password = $request->password;

        // Resolve email from login identifier
        if (str_contains($login, '@')) {
            // Input looks like an email
            $email = $login;
        } else {
            // Input is a phone number — look up the member
            $members = Member::where('phone', $login)->with('user')->get();

            if ($members->isEmpty()) {
                return back()
                    ->withErrors(['login' => 'No account found with this phone number.'])
                    ->onlyInput('login');
            }

            if ($members->count() > 1) {
                return back()
                    ->withErrors(['login' => 'Multiple accounts share this phone number. Please log in with your email address.'])
                    ->onlyInput('login');
            }

            $email = $members->first()->user->email;
        }

        if (Auth::attempt(['email' => $email, 'password' => $password], $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->status === 'inactive') {
                Auth::logout();
                return back()->withErrors(['login' => 'Your account is inactive. Contact the administrator.']);
            }

            AuditLog::record('login', 'user', $user->id, null, null, "User {$user->name} logged in");

            return $this->redirectByRole();
        }

        return back()
            ->withErrors(['login' => 'Invalid credentials. Please check and try again.'])
            ->onlyInput('login');
    }

    public function logout(Request $request)
    {
        AuditLog::record('logout', 'user', auth()->id(), null, null, "User " . auth()->user()->name . " logged out");

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    private function redirectByRole()
    {
        return Auth::user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('member.dashboard');
    }
}
