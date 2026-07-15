<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalCompanies = Company::count();
        $activeCompanies = Company::where('status', 'active')->count();
        $totalUsers = User::whereNotNull('company_id')->count();

        return view('super-admin.dashboard', compact('totalCompanies', 'activeCompanies', 'totalUsers'));
    }
}
