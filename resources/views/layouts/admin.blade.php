<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – SUMITY Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: true }">

<div class="flex h-screen overflow-hidden">

    {{-- ========== SIDEBAR ========== --}}
    <aside
        class="flex flex-col flex-shrink-0 bg-gray-900 overflow-y-auto transition-all duration-300 z-40"
        :class="sidebarOpen ? 'w-64' : 'w-0 lg:w-16'"
        style="min-width:0;">

        {{-- Brand --}}
        <div class="flex items-center gap-3 px-4 py-5 border-b border-gray-700/60 flex-shrink-0">
            <div class="w-9 h-9 bg-emerald-500 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg">
                <i class="fas fa-piggy-bank text-white text-base"></i>
            </div>
            <div x-show="sidebarOpen" x-cloak class="overflow-hidden whitespace-nowrap">
                <p class="text-white font-bold text-base leading-none">SUMITY</p>
                <p class="text-emerald-400 text-xs mt-0.5">Admin Panel</p>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden">

            {{-- Section: Main --}}
            <div x-show="sidebarOpen" x-cloak class="px-4 mb-1">
                <span class="text-gray-500 text-xs font-semibold uppercase tracking-widest">Main</span>
            </div>

            @php
            $link = fn($active) => $active
                ? 'flex items-center gap-3 mx-2 px-3 py-2.5 rounded-xl bg-emerald-600 text-white text-sm font-medium mb-0.5'
                : 'flex items-center gap-3 mx-2 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white text-sm font-medium mb-0.5 transition-colors';
            @endphp

            <a href="{{ route('admin.dashboard') }}" class="{{ $link(request()->routeIs('admin.dashboard')) }}">
                <i class="fas fa-gauge-high text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Dashboard</span>
            </a>

            {{-- Section: Management --}}
            <div x-show="sidebarOpen" x-cloak class="px-4 mt-5 mb-1">
                <span class="text-gray-500 text-xs font-semibold uppercase tracking-widest">Management</span>
            </div>
            <div x-show="!sidebarOpen" class="my-3 mx-4 border-t border-gray-700/50"></div>

            <a href="{{ route('admin.members.index') }}" class="{{ $link(request()->routeIs('admin.members.*')) }}">
                <i class="fas fa-users text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Members</span>
            </a>

            <a href="{{ route('admin.deposits.index') }}" class="{{ $link(request()->routeIs('admin.deposits.*')) }}">
                <i class="fas fa-hand-holding-dollar text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Deposits</span>
            </a>

            <a href="{{ route('admin.expenses.index') }}" class="{{ $link(request()->routeIs('admin.expenses.*')) }}">
                <i class="fas fa-receipt text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Expenses</span>
            </a>

            <a href="{{ route('admin.ledger.index') }}" class="{{ $link(request()->routeIs('admin.ledger.*')) }}">
                <i class="fas fa-book-open text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Ledger</span>
            </a>

            {{-- Section: Reports --}}
            <div x-show="sidebarOpen" x-cloak class="px-4 mt-5 mb-1">
                <span class="text-gray-500 text-xs font-semibold uppercase tracking-widest">Reports</span>
            </div>
            <div x-show="!sidebarOpen" class="my-3 mx-4 border-t border-gray-700/50"></div>

            <a href="{{ route('admin.reports.summary') }}" class="{{ $link(request()->routeIs('admin.reports.summary')) }}">
                <i class="fas fa-chart-pie text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Full Summary</span>
            </a>

            <a href="{{ route('admin.reports.daily') }}" class="{{ $link(request()->routeIs('admin.reports.daily')) }}">
                <i class="fas fa-calendar-day text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Daily Report</span>
            </a>

            <a href="{{ route('admin.reports.monthly') }}" class="{{ $link(request()->routeIs('admin.reports.monthly')) }}">
                <i class="fas fa-calendar-week text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Monthly Report</span>
            </a>

            <a href="{{ route('admin.reports.yearly') }}" class="{{ $link(request()->routeIs('admin.reports.yearly')) }}">
                <i class="fas fa-calendar text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Yearly Report</span>
            </a>

            {{-- Section: System --}}
            <div x-show="sidebarOpen" x-cloak class="px-4 mt-5 mb-1">
                <span class="text-gray-500 text-xs font-semibold uppercase tracking-widest">System</span>
            </div>
            <div x-show="!sidebarOpen" class="my-3 mx-4 border-t border-gray-700/50"></div>

            <a href="{{ route('admin.audit.index') }}" class="{{ $link(request()->routeIs('admin.audit.*')) }}">
                <i class="fas fa-shield-halved text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Audit Log</span>
            </a>

            <a href="{{ route('admin.password.edit') }}" class="{{ $link(request()->routeIs('admin.password.*')) }}">
                <i class="fas fa-key text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Change Password</span>
            </a>

        </nav>

        {{-- User Profile + Logout --}}
        <div class="border-t border-gray-700/60 p-3 flex-shrink-0">
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl">
                {{-- Avatar always visible --}}
                <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                {{-- Name shown only when sidebar is open --}}
                <div x-show="sidebarOpen" x-cloak class="flex-1 min-w-0 overflow-hidden">
                    <p class="text-white text-sm font-medium truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-emerald-400 text-xs mt-0.5">Administrator</p>
                </div>
            </div>
            {{-- Logout always visible, never inside x-show --}}
            <form method="POST" action="{{ route('logout') }}" class="mt-1">
                @csrf
                <button type="submit"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-colors text-sm font-medium">
                    <i class="fas fa-right-from-bracket w-5 text-center flex-shrink-0"></i>
                    <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    {{-- ========== MAIN AREA ========== --}}
    <div class="flex-1 flex flex-col overflow-hidden min-w-0">

        {{-- Top Bar --}}
        <header class="bg-white border-b border-gray-200 px-4 lg:px-6 h-16 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                {{-- Sidebar toggle --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-bars-staggered text-base"></i>
                </button>

                {{-- Breadcrumb --}}
                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-400">Admin</span>
                    <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                    <span class="text-gray-800 font-semibold">@yield('title', 'Dashboard')</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                {{-- Date --}}
                <div class="hidden sm:flex items-center gap-2 text-sm text-gray-500 bg-gray-50 border border-gray-200 px-3 py-1.5 rounded-lg">
                    <i class="fas fa-calendar-days text-emerald-500"></i>
                    <span>{{ now()->format('d M Y') }}</span>
                </div>

                {{-- Pending badge --}}
                @php $pendingCount = \App\Models\Deposit::where('status','pending')->count(); @endphp
                @if($pendingCount > 0)
                <a href="{{ route('admin.deposits.index', ['status' => 'pending']) }}"
                   class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-yellow-50 hover:bg-yellow-100 text-yellow-600 transition-colors">
                    <i class="fas fa-bell text-base"></i>
                    <span class="absolute -top-1 -right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-bold leading-none">
                        {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                    </span>
                </a>
                @endif

                {{-- User avatar + dropdown logout in header (always available) --}}
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-xl hover:bg-gray-100 transition-colors">
                        <div class="w-7 h-7 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="hidden md:block text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                        <i class="fas fa-chevron-down text-xs text-gray-400"></i>
                    </button>
                    <div x-show="open" x-cloak @click.away="open = false"
                         class="absolute right-0 top-full mt-2 w-44 bg-white rounded-xl shadow-lg border border-gray-200 py-1 z-50">
                        <div class="px-4 py-2 border-b border-gray-100">
                            <p class="text-xs font-semibold text-gray-800">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-400">Administrator</p>
                        </div>
                        <a href="{{ route('admin.password.edit') }}"
                           class="flex items-center gap-2 px-4 py-2.5 text-sm text-gray-700 hover:bg-gray-50 transition-colors">
                            <i class="fas fa-key text-gray-400"></i>
                            Change Password
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-right-from-bracket"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Flash Messages --}}
        <div class="px-4 lg:px-6 pt-4 space-y-2 flex-shrink-0">
            @if(session('success'))
                <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm"
                     x-data x-init="setTimeout(() => $el.remove(), 4000)">
                    <i class="fas fa-circle-check text-emerald-500 flex-shrink-0"></i>
                    <span>{{ session('success') }}</span>
                    <button @click="$el.closest('div').remove()" class="ml-auto text-emerald-400 hover:text-emerald-600">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif
            @if(session('error'))
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm"
                     x-data x-init="setTimeout(() => $el.remove(), 4000)">
                    <i class="fas fa-circle-exclamation text-red-500 flex-shrink-0"></i>
                    <span>{{ session('error') }}</span>
                    <button @click="$el.closest('div').remove()" class="ml-auto text-red-400 hover:text-red-600">
                        <i class="fas fa-xmark"></i>
                    </button>
                </div>
            @endif
        </div>

        {{-- Page Content --}}
        <main class="flex-1 overflow-y-auto px-4 lg:px-6 pb-6">
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
