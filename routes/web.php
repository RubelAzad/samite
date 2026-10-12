<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Member;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return auth()->user()->isAdmin()
            ? redirect()->route('admin.dashboard')
            : redirect()->route('member.dashboard');
    }

    return redirect()->route('login');
});

// Auth
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Members
    Route::get('/members', [Admin\MemberController::class, 'index'])->name('members.index');
    Route::get('/members/create', [Admin\MemberController::class, 'create'])->name('members.create');
    Route::post('/members', [Admin\MemberController::class, 'store'])->name('members.store');
    Route::get('/members/{member}', [Admin\MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{member}/edit', [Admin\MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{member}', [Admin\MemberController::class, 'update'])->name('members.update');
    Route::post('/members/{member}/toggle-status', [Admin\MemberController::class, 'toggleStatus'])->name('members.toggle-status');

    // Deposits
    Route::get('/deposits', [Admin\DepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [Admin\DepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [Admin\DepositController::class, 'store'])->name('deposits.store');
    Route::get('/deposits/{deposit}', [Admin\DepositController::class, 'show'])->name('deposits.show');
    Route::get('/deposits/{deposit}/receipt', [Admin\DepositController::class, 'receipt'])->name('deposits.receipt');
    Route::get('/deposits/{deposit}/edit', [Admin\DepositController::class, 'edit'])->name('deposits.edit');
    Route::put('/deposits/{deposit}', [Admin\DepositController::class, 'update'])->name('deposits.update');
    Route::delete('/deposits/{deposit}', [Admin\DepositController::class, 'destroy'])->name('deposits.destroy');
    Route::post('/deposits/{deposit}/approve', [Admin\DepositController::class, 'approve'])->name('deposits.approve');
    Route::post('/deposits/{deposit}/reject', [Admin\DepositController::class, 'reject'])->name('deposits.reject');

    // Withdrawals
    Route::get('/withdrawals', [Admin\WithdrawalController::class, 'index'])->name('withdrawals.index');
    Route::get('/withdrawals/create', [Admin\WithdrawalController::class, 'create'])->name('withdrawals.create');
    Route::post('/withdrawals', [Admin\WithdrawalController::class, 'store'])->name('withdrawals.store');
    Route::get('/withdrawals/{withdrawal}', [Admin\WithdrawalController::class, 'show'])->name('withdrawals.show');
    Route::post('/withdrawals/{withdrawal}/approve', [Admin\WithdrawalController::class, 'approve'])->name('withdrawals.approve');
    Route::post('/withdrawals/{withdrawal}/reject', [Admin\WithdrawalController::class, 'reject'])->name('withdrawals.reject');

    // Bank Accounts
    Route::get('/bank-accounts', [Admin\BankAccountController::class, 'index'])->name('bank-accounts.index');
    Route::get('/bank-accounts/create', [Admin\BankAccountController::class, 'create'])->name('bank-accounts.create');
    Route::post('/bank-accounts', [Admin\BankAccountController::class, 'store'])->name('bank-accounts.store');
    Route::get('/bank-accounts/{bankAccount}', [Admin\BankAccountController::class, 'show'])->name('bank-accounts.show');
    Route::get('/bank-accounts/{bankAccount}/edit', [Admin\BankAccountController::class, 'edit'])->name('bank-accounts.edit');
    Route::put('/bank-accounts/{bankAccount}', [Admin\BankAccountController::class, 'update'])->name('bank-accounts.update');
    Route::delete('/bank-accounts/{bankAccount}', [Admin\BankAccountController::class, 'destroy'])->name('bank-accounts.destroy');

    // Expenses
    Route::get('/expenses', [Admin\ExpenseController::class, 'index'])->name('expenses.index');
    Route::get('/expenses/create', [Admin\ExpenseController::class, 'create'])->name('expenses.create');
    Route::post('/expenses', [Admin\ExpenseController::class, 'store'])->name('expenses.store');
    Route::get('/expenses/{expense}', [Admin\ExpenseController::class, 'show'])->name('expenses.show');
    Route::get('/expenses/{expense}/edit', [Admin\ExpenseController::class, 'edit'])->name('expenses.edit');
    Route::put('/expenses/{expense}', [Admin\ExpenseController::class, 'update'])->name('expenses.update');
    Route::delete('/expenses/{expense}', [Admin\ExpenseController::class, 'destroy'])->name('expenses.destroy');

    // Ledger
    Route::get('/ledger', [Admin\LedgerController::class, 'index'])->name('ledger.index');

    // Reports
    Route::get('/reports/summary', [Admin\ReportController::class, 'summary'])->name('reports.summary');
    Route::get('/reports/summary/export', [Admin\ReportController::class, 'exportSummary'])->name('reports.summary.export');
    Route::get('/reports/summary/print', [Admin\ReportController::class, 'printSummary'])->name('reports.summary.print');
    Route::get('/reports/daily', [Admin\ReportController::class, 'daily'])->name('reports.daily');
    Route::get('/reports/monthly', [Admin\ReportController::class, 'monthly'])->name('reports.monthly');
    Route::get('/reports/yearly', [Admin\ReportController::class, 'yearly'])->name('reports.yearly');

    // Report exports (Excel)
    Route::get('/reports/daily/export', [Admin\ReportController::class, 'exportDaily'])->name('reports.daily.export');
    Route::get('/reports/monthly/export', [Admin\ReportController::class, 'exportMonthly'])->name('reports.monthly.export');
    Route::get('/reports/yearly/export', [Admin\ReportController::class, 'exportYearly'])->name('reports.yearly.export');

    // Report print (PDF via browser)
    Route::get('/reports/daily/print', [Admin\ReportController::class, 'printDaily'])->name('reports.daily.print');
    Route::get('/reports/monthly/print', [Admin\ReportController::class, 'printMonthly'])->name('reports.monthly.print');
    Route::get('/reports/yearly/print', [Admin\ReportController::class, 'printYearly'])->name('reports.yearly.print');

    // Member‑wise Payment Report
    Route::get('/reports/member-wise', [Admin\ReportController::class, 'memberWise'])->name('reports.member-wise');
    Route::get('/reports/member-wise/export', [Admin\ReportController::class, 'exportMemberWise'])->name('reports.member-wise.export');
    Route::get('/reports/member-wise/print', [Admin\ReportController::class, 'printMemberWise'])->name('reports.member-wise.print');

    // Audit Log
    Route::get('/audit-logs', [Admin\AuditLogController::class, 'index'])->name('audit.index');

    // Admin password (own)
    Route::get('/password', [Admin\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [Admin\PasswordController::class, 'update'])->name('password.update');

    // Reset a member's password
    Route::get('/users/{user}/reset-password', [Admin\PasswordController::class, 'editMember'])->name('users.password.edit');
    Route::put('/users/{user}/reset-password', [Admin\PasswordController::class, 'updateMember'])->name('users.password.update');
});

// Member Routes
Route::prefix('member')->name('member.')->middleware(['auth', 'member'])->group(function () {
    Route::get('/dashboard', [Member\DashboardController::class, 'index'])->name('dashboard');

    Route::get('/deposits', [Member\DepositController::class, 'index'])->name('deposits.index');
    Route::get('/deposits/create', [Member\DepositController::class, 'create'])->name('deposits.create');
    Route::post('/deposits', [Member\DepositController::class, 'store'])->name('deposits.store');

    Route::get('/withdrawals', [Member\WithdrawalController::class, 'index'])->name('withdrawals.index');

    Route::get('/ledger', [Member\LedgerController::class, 'index'])->name('ledger.index');

    // Member change password
    Route::get('/password', [Member\PasswordController::class, 'edit'])->name('password.edit');
    Route::put('/password', [Member\PasswordController::class, 'update'])->name('password.update');
});
