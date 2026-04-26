@extends('layouts.dashboard')

@section('title', 'Supplier Dashboard')
@section('page-title', 'Dashboard')

@section('page-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">inventory</span>
            </div>
            <span class="text-xs text-gray-500">Inventory</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ number_format($stats['inventory'], 0) }}</div>
        <p class="text-sm text-gray-500 mt-1">Units in stock</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">receipt_long</span>
            </div>
            <span class="text-xs text-gray-500">Orders</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['orders'] }}</div>
        <p class="text-sm text-gray-500 mt-1">To fulfill</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-orange/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-orange">payments</span>
            </div>
            <span class="text-xs text-gray-500">Revenue</span>
        </div>
        <div class="text-3xl font-bold text-white">${{ number_format($stats['revenue'], 0) }}</div>
        <p class="text-sm text-gray-500 mt-1">Total sales</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('supplier.inventory') }}" class="group p-6 rounded-2xl bg-primary-600/10 border border-primary-600/30 hover:bg-primary-600/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Manage Inventory</h3>
                <p class="text-sm text-gray-400">Track stock levels and restock products</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary-400">inventory</span>
            </div>
        </div>
    </a>
    <a href="{{ route('supplier.orders') }}" class="group p-6 rounded-2xl bg-accent-blue/10 border border-accent-blue/30 hover:bg-accent-blue/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">View Orders</h3>
                <p class="text-sm text-gray-400">Process pending orders and shipments</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-accent-blue">receipt_long</span>
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
        <h3 class="text-lg font-semibold text-white mb-2">Welcome, Supplier!</h3>
        <p class="text-gray-500 max-w-md mx-auto">
            Your supplier dashboard is set up. Use the quick actions above to manage your inventory and orders.
        </p>
    </div>
</div>
@endsection
