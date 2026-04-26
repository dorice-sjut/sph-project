@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')
@section('page-title', 'Admin Dashboard')

@section('page-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-8">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">people</span>
            </div>
            <span class="text-xs text-gray-500">Users</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $stats['users'] }}</div>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">agriculture</span>
            </div>
            <span class="text-xs text-gray-500">Farmers</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $stats['farmers'] }}</div>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-accent-purple/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-purple">shopping_cart</span>
            </div>
            <span class="text-xs text-gray-500">Buyers</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $stats['buyers'] }}</div>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-accent-orange/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-orange">inventory_2</span>
            </div>
            <span class="text-xs text-gray-500">Products</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $stats['products'] }}</div>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">receipt_long</span>
            </div>
            <span class="text-xs text-gray-500">Orders</span>
        </div>
        <div class="text-2xl font-bold text-white">{{ $stats['orders'] }}</div>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-10 h-10 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">payments</span>
            </div>
            <span class="text-xs text-gray-500">Revenue</span>
        </div>
        <div class="text-2xl font-bold text-white">${{ number_format($stats['revenue'], 0) }}</div>
    </div>
</div>

<!-- Main Content -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Recent Users -->
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Recent Users</h3>
            <a href="{{ route('admin.users') }}" class="text-sm text-primary-400 hover:text-primary-300">View all</a>
        </div>
        <div class="space-y-4">
            @foreach($recentUsers as $user)
                <div class="flex items-center gap-4 p-3 rounded-xl bg-dark-900/50">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff' }}"
                         alt="{{ $user->name }}"
                         class="w-10 h-10 rounded-lg object-cover">
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $user->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $user->role }}</p>
                    </div>
                    <span class="text-xs text-gray-500">{{ $user->created_at->diffForHumans() }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Recent Orders</h3>
            <a href="{{ route('admin.orders') }}" class="text-sm text-primary-400 hover:text-primary-300">View all</a>
        </div>
        <div class="space-y-4">
            @foreach($recentOrders as $order)
                <div class="flex items-center gap-4 p-3 rounded-xl bg-dark-900/50">
                    <div class="w-10 h-10 rounded-lg bg-primary-600/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary-400 text-sm">shopping_bag</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $order->product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $order->buyer->name }} → {{ $order->seller->name }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm font-semibold text-white">${{ number_format($order->total_price, 2) }}</p>
                        <span class="text-xs px-2 py-0.5 rounded-full bg-primary-600/20 text-primary-400">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
