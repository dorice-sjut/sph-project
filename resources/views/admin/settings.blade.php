@extends('layouts.dashboard')

@section('title', 'Admin Settings')
@section('page-title', 'Platform Settings')

@section('page-content')
<div class="max-w-2xl space-y-6">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-4">Platform Configuration</h3>
        <p class="text-gray-500">Platform settings will be available soon.</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <h3 class="text-lg font-semibold text-white mb-4">System Status</h3>
        <div class="space-y-3">
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Database</span>
                <span class="flex items-center gap-2 text-primary-400">
                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                    Operational
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Cache</span>
                <span class="flex items-center gap-2 text-primary-400">
                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                    Operational
                </span>
            </div>
            <div class="flex items-center justify-between">
                <span class="text-gray-400">Queue</span>
                <span class="flex items-center gap-2 text-primary-400">
                    <span class="w-2 h-2 rounded-full bg-current animate-pulse"></span>
                    Operational
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
