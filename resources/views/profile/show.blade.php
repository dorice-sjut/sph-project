@extends('layouts.dashboard')

@section('title', 'My Profile')
@section('page-title', 'My Profile')

@section('page-content')
<div class="max-w-3xl">
    <form method="POST" action="{{ route('profile') }}" enctype="multipart/form-data" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Avatar Section -->
        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center gap-6">
                <div class="relative">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff' }}"
                         alt="{{ $user->name }}"
                         class="w-24 h-24 rounded-2xl object-cover">
                    <label class="absolute -bottom-2 -right-2 p-2 rounded-lg bg-primary-600 text-white cursor-pointer hover:bg-primary-700 transition-colors">
                        <span class="material-symbols-outlined text-sm">camera_alt</span>
                        <input type="file" name="avatar" accept="image/*" class="hidden" onchange="this.form.submit()">
                    </label>
                </div>
                <div>
                    <h3 class="text-xl font-semibold text-white">{{ $user->name }}</h3>
                    <p class="text-gray-500 capitalize">{{ $user->role }}</p>
                    <p class="text-sm text-gray-600 mt-1">Member since {{ $user->created_at->format('M Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Basic Info -->
        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Basic Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Full Name</label>
                    <input type="text" name="name" value="{{ $user->name }}" required
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Email</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full px-4 py-3 rounded-xl bg-dark-900/50 border border-dark-700 text-gray-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Phone</label>
                    <input type="tel" name="phone" value="{{ $user->phone }}"
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                           placeholder="+255 XXX XXX XXX">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-300 mb-2">Location</label>
                    <input type="text" name="location" value="{{ $user->location }}"
                           class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                           placeholder="City, Country">
                </div>
            </div>
        </div>

        <!-- Bio -->
        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">About</h3>
            <textarea name="bio" rows="4"
                      class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                      placeholder="Tell us about yourself...">{{ $user->bio }}</textarea>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-3 gap-4">
            @if(isset($stats['products']))
                <div class="p-4 rounded-xl bg-dark-800 border border-dark-700 text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['products'] }}</p>
                    <p class="text-sm text-gray-500">Products</p>
                </div>
            @endif
            @if(isset($stats['orders']))
                <div class="p-4 rounded-xl bg-dark-800 border border-dark-700 text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['orders'] }}</p>
                    <p class="text-sm text-gray-500">Orders</p>
                </div>
            @endif
            @if(isset($stats['rating']))
                <div class="p-4 rounded-xl bg-dark-800 border border-dark-700 text-center">
                    <p class="text-2xl font-bold text-white">{{ $stats['rating'] }}</p>
                    <p class="text-sm text-gray-500">Rating</p>
                </div>
            @endif
        </div>

        <div class="flex gap-4">
            <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined">save</span>
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
