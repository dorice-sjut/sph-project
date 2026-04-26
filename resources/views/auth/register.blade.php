@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
<div class="min-h-screen flex items-center justify-center p-4 py-8">
    <div class="w-full max-w-lg">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex justify-center">
                <x-logo size="lg" :show-text="true" />
            </a>
        </div>

        <!-- Form Card -->
        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-8 card-shadow">
            <h2 class="text-2xl font-bold text-white mb-2">Create your account</h2>
            <p class="text-gray-400 mb-6">Join the future of agriculture</p>

            @if($errors->any())
                <div class="mb-6 p-4 rounded-xl bg-red-900/30 border border-red-700 text-red-300 text-sm">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-500">person</span>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none transition-colors"
                               placeholder="John Doe">
                    </div>
                </div>

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
                    <label class="block text-sm font-medium text-gray-300 mb-2">I am a</label>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($roles as $role)
                            <label class="cursor-pointer">
                                <input type="radio" name="role" value="{{ $role }}" class="peer sr-only" {{ old('role') === $role ? 'checked' : '' }} required>
                                <div class="px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-center capitalize text-gray-400 peer-checked:bg-primary-600/20 peer-checked:border-primary-600 peer-checked:text-primary-400 transition-all">
                                    {{ $role }}
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-500">location_on</span>
                        <input type="text"
                               name="location"
                               value="{{ old('location') }}"
                               class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none transition-colors"
                               placeholder="City, Country">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone (optional)</label>
                    <div class="relative">
                        <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-500">phone</span>
                        <input type="tel"
                               name="phone"
                               value="{{ old('phone') }}"
                               class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none transition-colors"
                               placeholder="+255 XXX XXX XXX">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
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
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-2">Confirm</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-4 top-3.5 text-gray-500">lock</span>
                            <input type="password"
                                   name="password_confirmation"
                                   required
                                   class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none transition-colors"
                                   placeholder="••••••••">
                        </div>
                    </div>
                </div>

                <button type="submit" class="w-full py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center justify-center gap-2">
                    Create Account
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </button>
            </form>

            <div class="mt-6 text-center">
                <p class="text-gray-400 text-sm">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-primary-400 hover:text-primary-300 font-medium transition-colors">Sign in</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
