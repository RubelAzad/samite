<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – SUMITY Member</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="bg-gray-100 font-sans" x-data="{ open: false }">

<div class="min-h-screen flex flex-col">
    <!-- Top Nav -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-emerald-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-piggy-bank text-white"></i>
                </div>
                <div>
                    <p class="font-bold text-gray-800 leading-none">SUMITY</p>
                    <p class="text-xs text-gray-500">Member Portal</p>
                </div>
            </div>

            <!-- Desktop Nav -->
            <div class="hidden sm:flex items-center gap-1">
                <a href="{{ route('member.dashboard') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.dashboard') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-home mr-1"></i> Dashboard
                </a>
                <a href="{{ route('member.deposits.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.deposits.*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-hand-holding-usd mr-1"></i> My Deposits
                </a>
                <a href="{{ route('member.ledger.index') }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition {{ request()->routeIs('member.ledger.*') ? 'bg-emerald-600 text-white' : 'text-gray-600 hover:bg-gray-100' }}">
                    <i class="fas fa-book mr-1"></i> My Ledger
                </a>
            </div>

            <!-- User Menu -->
            <div class="flex items-center gap-3" x-data="{ menu: false }">
                <div class="relative">
                    <button @click="menu = !menu" class="flex items-center gap-2 text-sm text-gray-700 hover:text-gray-900">
                        <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center font-bold text-emerald-700 text-sm">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden sm:block">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="menu" x-cloak @click.away="menu = false"
                         class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-800 truncate">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Member</p>
                        </div>
                        <a href="{{ route('member.password.edit') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50">
                            <i class="fas fa-key text-gray-400"></i> Change Password
                        </a>
                        <div class="border-t border-gray-100"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Nav -->
        <div class="sm:hidden border-t border-gray-100 flex">
            <a href="{{ route('member.dashboard') }}"
               class="flex-1 py-3 text-center text-xs font-medium {{ request()->routeIs('member.dashboard') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500' }}">
                <i class="fas fa-home block text-lg mb-0.5"></i> Home
            </a>
            <a href="{{ route('member.deposits.index') }}"
               class="flex-1 py-3 text-center text-xs font-medium {{ request()->routeIs('member.deposits.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500' }}">
                <i class="fas fa-hand-holding-usd block text-lg mb-0.5"></i> Deposits
            </a>
            <a href="{{ route('member.ledger.index') }}"
               class="flex-1 py-3 text-center text-xs font-medium {{ request()->routeIs('member.ledger.*') ? 'text-emerald-600 border-b-2 border-emerald-600' : 'text-gray-500' }}">
                <i class="fas fa-book block text-lg mb-0.5"></i> Ledger
            </a>
        </div>
    </nav>

    <!-- Flash -->
    <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 pt-4">
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2" x-data x-init="setTimeout(() => $el.remove(), 5000)">
            <i class="fas fa-check-circle text-emerald-500"></i> {{ session('success') }}
        </div>
        @endif
        @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-4 flex items-center gap-2" x-data x-init="setTimeout(() => $el.remove(), 5000)">
            <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
        </div>
        @endif
    </div>

    <!-- Content -->
    <main class="flex-1 max-w-5xl mx-auto w-full px-4 sm:px-6 pb-8">
        @yield('content')
    </main>

    <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-400">
        &copy; {{ date('Y') }} SUMITY – Group Savings Management
    </footer>
</div>

@stack('scripts')
</body>
</html>
