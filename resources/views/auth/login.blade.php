@extends('layouts.app')

@section('title', 'Login - StreetFoodies')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full mx-auto">
        {{-- Logo --}}
        <div class="text-center mb-8">
            <h1 class="text-4xl font-bold text-primary">🍜 StreetFoodies</h1>
            <p class="text-gray-400 mt-2">Sign in to your account</p>
        </div>

        {{-- Card --}}
        <div class="bg-dark rounded-2xl shadow-xl p-8 border border-gray-700/50">

            {{-- Flash Error Messages --}}
            @if (session('error'))
                <div class="mb-4 p-4 rounded-lg bg-red-500/10 border border-red-500/50 text-red-400 text-sm">
                    {{ session('error') }}
                </div>
            @endif

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

            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="mb-5">
                    <label for="email" class="block text-sm font-medium text-gray-300 mb-2">Email Address</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 rounded-lg bg-darker border border-gray-600 text-gray-100 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition"
                        placeholder="you@example.com"
                    >
                    @error('email')
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
                            onclick="togglePassword()"
                            class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-400 hover:text-gray-200 transition"
                        >
                            <svg id="eye-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg id="eye-off-icon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center justify-between mb-6">
                    <label class="flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            name="remember"
                            class="w-4 h-4 rounded border-gray-600 bg-darker text-primary focus:ring-primary focus:ring-offset-0"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span class="ml-2 text-sm text-gray-400">Remember me</span>
                    </label>
                </div>

                {{-- Login Button --}}
                <button
                    type="submit"
                    class="w-full py-3 px-4 rounded-lg bg-primary hover:bg-primary/80 text-white font-semibold transition duration-200 focus:outline-none focus:ring-2 focus:ring-primary focus:ring-offset-2 focus:ring-offset-dark"
                >
                    Sign In
                </button>
            </form>

            {{-- Register Link --}}
            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary hover:text-primary/80 font-medium transition">
                        Create one here
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function togglePassword() {
        const passwordInput = document.getElementById('password');
        const eyeIcon = document.getElementById('eye-icon');
        const eyeOffIcon = document.getElementById('eye-off-icon');

        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            eyeIcon.classList.add('hidden');
            eyeOffIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            eyeIcon.classList.remove('hidden');
            eyeOffIcon.classList.add('hidden');
        }
    }
</script>
@endpush
@endsection
