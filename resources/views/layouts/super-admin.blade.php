<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') – Samite Super Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
        body { font-family: 'Segoe UI', system-ui, sans-serif; }
    </style>
</head>
<body class="bg-gray-100" x-data="{ sidebarOpen: false }">

<div class="flex h-screen overflow-hidden">

    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden"></div>

    {{-- ========== SIDEBAR ========== --}}
    <aside
        class="flex flex-col bg-gray-900 overflow-y-auto transition-all duration-300 z-40"
        :class="sidebarOpen
            ? 'fixed inset-y-0 left-0 w-64 shadow-2xl lg:shadow-none lg:static'
            : 'hidden lg:flex lg:w-16'">

        <div class="flex items-center gap-3 px-4 py-5 border-b border-gray-700/60 flex-shrink-0">
            <div class="w-9 h-9 rounded-xl flex-shrink-0 shadow-lg bg-indigo-600 flex items-center justify-center text-white">
                <i class="fas fa-building-columns"></i>
            </div>
            <div x-show="sidebarOpen" x-cloak class="overflow-hidden whitespace-nowrap">
                <p class="text-white font-bold text-base leading-none">Samite</p>
                <p class="text-indigo-400 text-xs mt-0.5">Super Admin</p>
            </div>
        </div>

        <nav class="flex-1 py-4 overflow-y-auto overflow-x-hidden">
            @php
            $link = fn($active) => $active
                ? 'flex items-center gap-3 mx-2 px-3 py-2.5 rounded-xl bg-indigo-600 text-white text-sm font-medium mb-0.5'
                : 'flex items-center gap-3 mx-2 px-3 py-2.5 rounded-xl text-gray-400 hover:bg-white/10 hover:text-white text-sm font-medium mb-0.5 transition-colors';
            @endphp

            <a href="{{ route('super-admin.dashboard') }}" class="{{ $link(request()->routeIs('super-admin.dashboard')) }}">
                <i class="fas fa-gauge-high text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Dashboard</span>
            </a>

            <a href="{{ route('super-admin.companies.index') }}" class="{{ $link(request()->routeIs('super-admin.companies.*')) }}">
                <i class="fas fa-building text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Companies</span>
            </a>

            <a href="{{ route('super-admin.packages.index') }}" class="{{ $link(request()->routeIs('super-admin.packages.*')) }}">
                <i class="fas fa-box text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Packages</span>
            </a>

            {{-- Section: Reports --}}
            <div x-show="sidebarOpen" x-cloak class="px-4 mt-5 mb-1">
                <span class="text-gray-500 text-xs font-semibold uppercase tracking-widest">Reports</span>
            </div>
            <div x-show="!sidebarOpen" class="my-3 mx-4 border-t border-gray-700/50"></div>

            <a href="{{ route('super-admin.reports.index') }}" class="{{ $link(request()->routeIs('super-admin.reports.index')) }}">
                <i class="fas fa-chart-line text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Overview</span>
            </a>

            <a href="{{ route('super-admin.reports.companies') }}" class="{{ $link(request()->routeIs('super-admin.reports.companies')) }}">
                <i class="fas fa-list-check text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Company Status</span>
            </a>

            <a href="{{ route('super-admin.reports.payments') }}" class="{{ $link(request()->routeIs('super-admin.reports.payments')) }}">
                <i class="fas fa-money-bill-transfer text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Payment History</span>
            </a>

            <a href="{{ route('super-admin.reports.daily') }}" class="{{ $link(request()->routeIs('super-admin.reports.daily')) }}">
                <i class="fas fa-calendar-day text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Daily Revenue</span>
            </a>

            <a href="{{ route('super-admin.reports.monthly') }}" class="{{ $link(request()->routeIs('super-admin.reports.monthly')) }}">
                <i class="fas fa-calendar-week text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Monthly Revenue</span>
            </a>

            <a href="{{ route('super-admin.reports.yearly') }}" class="{{ $link(request()->routeIs('super-admin.reports.yearly')) }}">
                <i class="fas fa-calendar text-base w-5 text-center flex-shrink-0"></i>
                <span x-show="sidebarOpen" x-cloak class="whitespace-nowrap">Yearly Revenue</span>
            </a>
        </nav>

        <div class="border-t border-gray-700/60 p-3 flex-shrink-0">
            <div class="flex items-center gap-3 px-2 py-2 rounded-xl">
                <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center text-white text-sm font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div x-show="sidebarOpen" x-cloak class="flex-1 min-w-0 overflow-hidden">
                    <p class="text-white text-sm font-medium truncate leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-indigo-400 text-xs mt-0.5">Super Admin</p>
                </div>
            </div>
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

        <header class="bg-white border-b border-gray-200 px-4 lg:px-6 h-16 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center gap-4">
                <button @click="sidebarOpen = !sidebarOpen"
                        class="w-9 h-9 flex items-center justify-center rounded-xl hover:bg-gray-100 text-gray-500 hover:text-gray-700 transition-colors">
                    <i class="fas fa-bars-staggered text-base"></i>
                </button>

                <div class="flex items-center gap-2 text-sm">
                    <span class="text-gray-400">Super Admin</span>
                    <i class="fas fa-chevron-right text-xs text-gray-300"></i>
                    <span class="text-gray-800 font-semibold">@yield('title', 'Dashboard')</span>
                </div>
            </div>
        </header>

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
            @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <main class="flex-1 overflow-y-auto px-4 lg:px-6 pb-6">
            @yield('content')
        </main>
    </div>
</div>

</body>
</html>
