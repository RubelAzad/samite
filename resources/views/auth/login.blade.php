<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – SUMITY</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>[x-cloak] { display: none !important; }</style>
</head>
<body class="min-h-screen bg-gradient-to-br from-emerald-900 via-teal-800 to-cyan-900 flex items-center justify-center p-4">

<div class="w-full max-w-md">
    <!-- Logo -->
    <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-xl mb-4">
            <i class="fas fa-piggy-bank text-4xl text-emerald-600"></i>
        </div>
        <h1 class="text-4xl font-bold text-white tracking-wide">SUMITY</h1>
        <p class="text-emerald-200 mt-1 text-sm">Group Savings & Expense Management</p>
    </div>

    <!-- Card -->
    <div class="bg-white rounded-2xl shadow-2xl p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-1">Welcome back</h2>
        <p class="text-gray-400 text-sm mb-6">Sign in with your email or phone number</p>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4 flex items-center gap-2">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}"
              x-data="{ loginVal: '{{ old('login') }}', isPhone: {{ str_contains(old('login', ''), '@') ? 'false' : 'true' }}, showPass: false }">
            @csrf

            {{-- Login field --}}
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                    Email or Phone Number
                </label>

                {{-- Toggle tabs --}}
                <div class="flex rounded-lg border border-gray-200 overflow-hidden mb-3">
                    <button type="button"
                            @click="isPhone = false"
                            :class="!isPhone ? 'bg-emerald-600 text-white' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'"
                            class="flex-1 py-2 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors">
                        <i class="fas fa-envelope text-xs"></i> Email
                    </button>
                    <button type="button"
                            @click="isPhone = true"
                            :class="isPhone ? 'bg-emerald-600 text-white' : 'bg-gray-50 text-gray-500 hover:bg-gray-100'"
                            class="flex-1 py-2 text-xs font-semibold flex items-center justify-center gap-1.5 transition-colors">
                        <i class="fas fa-phone text-xs"></i> Phone
                    </button>
                </div>

                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                        <i :class="isPhone ? 'fa-phone' : 'fa-envelope'" class="fas text-gray-400 text-sm"></i>
                    </span>
                    <input type="text"
                           name="login"
                           x-model="loginVal"
                           x-ref="loginInput"
                           :placeholder="isPhone ? '01XXXXXXXXX' : 'you@example.com'"
                           :inputmode="isPhone ? 'numeric' : 'email'"
                           required autocomplete="username"
                           class="w-full pl-10 pr-4 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition
                                  {{ $errors->has('login') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                </div>

                @error('login')
                    <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                        <i class="fas fa-circle-exclamation text-xs"></i> {{ $message }}
                    </p>
                @enderror
            </div>

            {{-- Password --}}
            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                        <i class="fas fa-lock text-sm"></i>
                    </span>
                    <input :type="showPass ? 'text' : 'password'"
                           name="password"
                           required autocomplete="current-password"
                           class="w-full pl-10 pr-11 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition"
                           placeholder="••••••••">
                    <button type="button" @click="showPass = !showPass"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                        <i :class="showPass ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                    </button>
                </div>
            </div>

            {{-- Remember --}}
            <div class="flex items-center justify-between mb-6">
                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer select-none">
                    <input type="checkbox" name="remember" class="rounded text-emerald-600">
                    Remember me
                </label>
            </div>

            <button type="submit"
                    class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-semibold py-3 rounded-xl transition duration-200 flex items-center justify-center gap-2 text-sm">
                <i class="fas fa-sign-in-alt"></i> Sign In
            </button>
        </form>

        {{-- Hint --}}
        <div class="mt-5 bg-gray-50 border border-gray-200 rounded-xl p-3.5 text-xs text-gray-500 space-y-1">
            <p class="font-semibold text-gray-600">Login options:</p>
            <p><i class="fas fa-envelope text-emerald-500 w-4"></i> Use your registered email address</p>
            <p><i class="fas fa-phone text-emerald-500 w-4"></i> Use your registered phone number</p>
        </div>
    </div>

    <p class="text-center text-emerald-200 text-xs mt-6">
        &copy; {{ date('Y') }} SUMITY – Group Savings Management
    </p>
</div>

</body>
</html>
