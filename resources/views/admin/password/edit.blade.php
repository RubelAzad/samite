@extends('layouts.admin')

@section('title', $self ? 'Change Password' : 'Reset Member Password')

@section('content')
<div class="mt-6 max-w-xl">

    <div class="mb-5">
        @if($self)
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> Back to Dashboard
            </a>
        @else
            <a href="{{ route('admin.members.show', $target->member) }}" class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-800 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i> Back to Member Profile
            </a>
        @endif
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">

        {{-- Header --}}
        <div class="px-6 py-5 border-b border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 {{ $self ? 'bg-emerald-100' : 'bg-blue-100' }} rounded-xl flex items-center justify-center">
                <i class="fas fa-key {{ $self ? 'text-emerald-600' : 'text-blue-600' }}"></i>
            </div>
            <div>
                @if($self)
                    <h2 class="text-lg font-bold text-gray-800">Change Your Password</h2>
                    <p class="text-xs text-gray-400">No current password required for admin accounts</p>
                @else
                    <h2 class="text-lg font-bold text-gray-800">Reset Password</h2>
                    <p class="text-xs text-gray-400">
                        Setting new password for
                        <span class="font-semibold text-blue-600">{{ $target->name }}</span>
                        ({{ $target->member->member_code ?? $target->email }})
                    </p>
                @endif
            </div>
        </div>

        @if(!$self)
        {{-- Warning banner for member reset --}}
        <div class="mx-6 mt-4 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex gap-3">
            <i class="fas fa-triangle-exclamation text-amber-500 mt-0.5 flex-shrink-0"></i>
            <p class="text-sm text-amber-800">
                You are resetting the password for <strong>{{ $target->name }}</strong>.
                The member will need to use this new password to log in.
                Please inform them of the change.
            </p>
        </div>
        @endif

        <form method="POST"
              action="{{ $self ? route('admin.password.update') : route('admin.users.password.update', $target) }}"
              x-data="{
                  showNew: false, showConfirm: false,
                  strength: 0, password: '',
                  calcStrength() {
                      let s = 0, p = this.password;
                      if (p.length >= 8)           s++;
                      if (/[A-Z]/.test(p))         s++;
                      if (/[0-9]/.test(p))         s++;
                      if (/[^A-Za-z0-9]/.test(p)) s++;
                      this.strength = s;
                  }
              }">
            @csrf
            @method('PUT')

            <div class="px-6 py-5 space-y-5">

                {{-- New Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input :type="showNew ? 'text' : 'password'"
                               name="password"
                               x-model="password"
                               @input="calcStrength()"
                               required minlength="8" autocomplete="new-password"
                               class="w-full pl-10 pr-11 py-3 border rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent
                                      {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                               placeholder="Minimum 8 characters">
                        <button type="button" @click="showNew = !showNew"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <i :class="showNew ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                        </button>
                    </div>

                    {{-- Strength bar --}}
                    <div x-show="password.length > 0" class="mt-2 space-y-1">
                        <div class="flex gap-1">
                            <template x-for="i in 4">
                                <div class="flex-1 h-1.5 rounded-full transition-all duration-300"
                                     :class="{
                                        'bg-red-400':    i <= strength && strength === 1,
                                        'bg-orange-400': i <= strength && strength === 2,
                                        'bg-yellow-400': i <= strength && strength === 3,
                                        'bg-emerald-500':i <= strength && strength === 4,
                                        'bg-gray-200':   i > strength
                                     }"></div>
                            </template>
                        </div>
                        <p class="text-xs"
                           :class="{
                               'text-red-500':    strength === 1,
                               'text-orange-500': strength === 2,
                               'text-yellow-600': strength === 3,
                               'text-emerald-600':strength === 4,
                           }"
                           x-text="['','Weak','Fair','Good','Strong'][strength]"></p>
                    </div>

                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600 flex items-center gap-1">
                            <i class="fas fa-circle-exclamation"></i> {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1.5">
                        Confirm New Password <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center text-gray-400">
                            <i class="fas fa-lock text-sm"></i>
                        </span>
                        <input :type="showConfirm ? 'text' : 'password'"
                               name="password_confirmation"
                               required autocomplete="new-password"
                               class="w-full pl-10 pr-11 py-3 border border-gray-300 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                               placeholder="Re-enter new password">
                        <button type="button" @click="showConfirm = !showConfirm"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600">
                            <i :class="showConfirm ? 'fa-eye-slash' : 'fa-eye'" class="fas text-sm"></i>
                        </button>
                    </div>
                </div>

                {{-- Password Rules Checklist --}}
                <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-500 space-y-1.5">
                    <p class="font-semibold text-gray-600 mb-2">Password requirements:</p>
                    <p :class="password.length >= 8 ? 'text-emerald-600' : ''">
                        <i :class="password.length >= 8 ? 'fa-circle-check text-emerald-500' : 'fa-circle text-gray-300'" class="fas mr-1.5"></i>
                        At least 8 characters
                    </p>
                    <p :class="/[A-Z]/.test(password) ? 'text-emerald-600' : ''">
                        <i :class="/[A-Z]/.test(password) ? 'fa-circle-check text-emerald-500' : 'fa-circle text-gray-300'" class="fas mr-1.5"></i>
                        One uppercase letter
                    </p>
                    <p :class="/[0-9]/.test(password) ? 'text-emerald-600' : ''">
                        <i :class="/[0-9]/.test(password) ? 'fa-circle-check text-emerald-500' : 'fa-circle text-gray-300'" class="fas mr-1.5"></i>
                        One number
                    </p>
                </div>

            </div>

            <div class="px-6 pb-6 flex gap-3">
                <button type="submit"
                        class="flex-1 {{ $self ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-semibold py-3 rounded-xl transition-colors text-sm flex items-center justify-center gap-2">
                    <i class="fas fa-shield-halved"></i>
                    {{ $self ? 'Update Password' : 'Reset Password' }}
                </button>
                @if($self)
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition-colors text-sm">
                        Cancel
                    </a>
                @else
                    <a href="{{ route('admin.members.show', $target->member) }}"
                       class="flex-1 text-center bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold py-3 rounded-xl transition-colors text-sm">
                        Cancel
                    </a>
                @endif
            </div>
        </form>
    </div>
</div>
@endsection
