@extends('layouts.app')

@section('title', 'Sign In')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex justify-center">
                <x-logo size="lg" :show-text="true" />
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-8 card-shadow">
            <h2 class="text-2xl font-bold text-white mb-2">Welcome back</h2>
            <p class="text-gray-400 mb-6">Sign in to your account to continue</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-900/30 border border-red-700 text-red-300 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-500">mail</span>
                        <input type="email"
                               name="email"
                               value="{{ old('email') }}"
                               required
                               class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none transition-colors"
                               placeholder="you@example.com">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Password</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-500">lock</span>
                        <input type="password"
                               name="password"
                               required
                               class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none transition-colors"
                               placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-dark-600 bg-dark-900 text-primary-600 focus:ring-primary-600">
                        <span class="text-sm text-gray-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-primary-400 hover:text-primary-300 transition-colors">Forgot password?</a>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center justify-center gap-2">
                    Sign In
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    Don't have an account?
                    <a href="{{ route('register') }}" class="text-primary-400 hover:text-primary-300 font-medium transition-colors">Sign up</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
