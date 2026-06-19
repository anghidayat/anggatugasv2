@extends('layouts.app')

@section('title', 'Register - StreetFoodies')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full mx-auto">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary">🍜 StreetFoodies</h1>
            <p class="text-gray-400 mt-2">Create your account</p>
        </div>

        {{-- Card --}}
        <div class="bg-dark rounded-2xl shadow-xl p-8 border border-gray-700/50">

            {{-- Validation Errors Summary --}}
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/50 text-red-400 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Name --}}
                <div class="mb-5">
                    <label for="name" class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 rounded-lg bg-darker border border-gray-600 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="John Doe"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 rounded-lg bg-darker border border-gray-600 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="you@example.com"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div class="mb-5">
                    <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                        Phone Number <span class="text-gray-500">(optional)</span>
                    </label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 rounded-lg bg-darker border border-gray-600 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="+62 812 3456 7890"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="mb-5">
                    <label for="password" class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            required
                            class="w-full px-4 py-3 rounded-lg bg-darker border border-gray-600 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition pr-12"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password')"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-200 transition"
                        >
                            <svg class="h-5 w-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div class="mb-5">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">Confirm Password</label>
                    <div class="relative">
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            required
                            class="w-full px-4 py-3 rounded-lg bg-darker border border-gray-600 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition pr-12"
                            placeholder="••••••••"
                        >
                        <button
                            type="button"
                            onclick="togglePassword('password_confirmation')"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-200 transition"
                        >
                            <svg class="h-5 w-5 eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Role Selector --}}
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-300 mb-3">I want to join as</label>
                    <div class="grid grid-cols-2 gap-4">
                        {{-- Vendor Card --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="vendor" class="hidden peer" {{ old('role') === 'vendor' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-gray-600 bg-darker text-center transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/10 hover:border-gray-500">
                                <div class="text-3xl mb-2">🏪</div>
                                <h3 class="font-semibold text-gray-200">Vendor</h3>
                                <p class="text-xs text-gray-500 mt-1">Sell your street food</p>
                            </div>
                        </label>

                        {{-- Buyer Card --}}
                        <label class="cursor-pointer">
                            <input type="radio" name="role" value="buyer" class="hidden peer" {{ old('role', 'buyer') === 'buyer' ? 'checked' : '' }}>
                            <div class="p-4 rounded-xl border-2 border-gray-600 bg-darker text-center transition-all duration-200 peer-checked:border-primary peer-checked:bg-primary/10 hover:border-gray-500">
                                <div class="text-3xl mb-2">🛒</div>
                                <h3 class="font-semibold text-gray-200">Buyer</h3>
                                <p class="text-xs text-gray-500 mt-1">Discover & order food</p>
                            </div>
                        </label>
                    </div>
                    @error('role')
                        <p class="mt-2 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Register Button --}}
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-lg bg-primary hover:bg-primary/80 text-white font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-dark"
                >
                    Create Account
                </button>
            </form>

            {{-- Login Link --}}
            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary hover:text-primary/80 font-medium transition">
                        Sign in here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        input.type = input.type === 'password' ? 'text' : 'password';
    }
</script>
@endpush
@endsection
