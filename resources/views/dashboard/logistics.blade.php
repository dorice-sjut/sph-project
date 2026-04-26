@extends('layouts.dashboard')

@section('title', 'Logistics Dashboard')
@section('page-title', 'Dashboard')

@section('page-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-orange/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-orange">local_shipping</span>
            </div>
            <span class="text-xs text-gray-500">Active Deliveries</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['active'] }}</div>
        <p class="text-sm text-gray-500 mt-1">In transit</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">check_circle</span>
            </div>
            <span class="text-xs text-gray-500">Completed</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['completed'] }}</div>
        <p class="text-sm text-gray-500 mt-1">Deliveries</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">route</span>
            </div>
            <span class="text-xs text-gray-500">Routes</span>
        </div>
        <div class="text-3xl font-bold text-white">12</div>
        <p class="text-sm text-gray-500 mt-1">Active routes</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('logistics.deliveries') }}" class="group p-6 rounded-2xl bg-accent-orange/10 border border-accent-orange/30 hover:bg-accent-orange/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Deliveries</h3>
                <p class="text-sm text-gray-400">Track and manage shipments</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-orange/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-accent-orange">local_shipping</span>
            </div>
        </div>
    </a>
    <a href="{{ route('logistics.routes') }}" class="group p-6 rounded-2xl bg-accent-blue/10 border border-accent-blue/30 hover:bg-accent-blue/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Routes</h3>
                <p class="text-sm text-gray-400">Optimize delivery routes</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-accent-blue">map</span>
            </div>
        </div>
    </a>
</div>

<!-- Content Placeholder -->
<div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
    <div class="text-center py-12">
        <div class="w-16 h-16 rounded-xl bg-dark-700 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-3xl text-gray-500">local_shipping</span>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">Welcome, Logistics Partner!</h3>
        <p class="text-gray-500 max-w-md mx-auto">
            Your logistics dashboard helps you manage deliveries and optimize routes across the agricultural supply chain.
        </p>
    </div>
</div>
@endsection
