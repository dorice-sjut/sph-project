@extends('layouts.dashboard')

@section('title', 'Settings')
@section('page-title', 'Settings')

@section('page-content')
<div class="max-w-2xl space-y-6">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-4">Account Settings</h3>
        <p class="text-gray-500">Account settings will be available soon.</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-4">Notifications</h3>
        <div class="space-y-4">
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-white font-medium">Email Notifications</p>
                    <p class="text-sm text-gray-500">Receive updates via email</p>
                </div>
                <input type="checkbox" checked class="w-5 h-5 rounded border-dark-600 bg-dark-900 text-primary-600">
            </label>
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-white font-medium">Order Updates</p>
                    <p class="text-sm text-gray-500">Get notified about order status</p>
                </div>
                <input type="checkbox" checked class="w-5 h-5 rounded border-dark-600 bg-dark-900 text-primary-600">
            </label>
            <label class="flex items-center justify-between cursor-pointer">
                <div>
                    <p class="text-white font-medium">Messages</p>
                    <p class="text-sm text-gray-500">Get notified about new messages</p>
                </div>
                <input type="checkbox" checked class="w-5 h-5 rounded border-dark-600 bg-dark-900 text-primary-600">
            </label>
        </div>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-4 text-red-400">Danger Zone</h3>
        <p class="text-gray-500 mb-4">These actions are irreversible.</p>
        <button class="px-4 py-2 rounded-lg border border-red-600/50 text-red-400 hover:bg-red-600/10 transition-colors">
            Delete Account
        </button>
    </div>
</div>
@endsection
