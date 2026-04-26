@extends('layouts.dashboard')

@section('title', 'Buyer Dashboard')
@section('page-title', 'Dashboard')

@section('page-content')
<!-- Stats Cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">receipt_long</span>
            </div>
            <span class="text-xs text-gray-500">My Orders</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['orders'] }}</div>
        <p class="text-sm text-gray-500 mt-1">Total placed</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">payments</span>
            </div>
            <span class="text-xs text-gray-500">Total Spent</span>
        </div>
        <div class="text-3xl font-bold text-white">${{ number_format($stats['spent'], 0) }}</div>
        <p class="text-sm text-gray-500 mt-1">All time</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-accent-purple/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-purple">chat</span>
            </div>
            <span class="text-xs text-gray-500">Messages</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $stats['messages'] }}</div>
        <p class="text-sm text-gray-500 mt-1">Unread</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
    <a href="{{ route('buyer.marketplace') }}" class="group p-6 rounded-2xl bg-primary-600/10 border border-primary-600/30 hover:bg-primary-600/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Browse Marketplace</h3>
                <p class="text-sm text-gray-400">Discover fresh produce from local farmers</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-primary-400">shopping_basket</span>
            </div>
        </div>
    </a>
    <a href="{{ route('market.prices') }}" class="group p-6 rounded-2xl bg-accent-blue/10 border border-accent-blue/30 hover:bg-accent-blue/20 transition-all">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-lg font-semibold text-white mb-1">Market Prices</h3>
                <p class="text-sm text-gray-400">Check current prices before buying</p>
            </div>
            <div class="w-12 h-12 rounded-xl bg-accent-blue/20 flex items-center justify-center group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-accent-blue">trending_up</span>
            </div>
        </div>
    </a>
</div>

<!-- Main Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Recent Orders -->
    <div class="lg:col-span-2 p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Recent Orders</h3>
            <a href="{{ route('buyer.orders') }}" class="text-sm text-primary-400 hover:text-primary-300 flex items-center gap-1">
                View all <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </a>
        </div>

        @if($recentOrders->count() > 0)
            <div class="space-y-4">
                @foreach($recentOrders as $order)
                    <div class="flex items-center gap-4 p-4 rounded-xl bg-dark-900/50">
                        <div class="w-12 h-12 rounded-lg bg-primary-600/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-400">shopping_bag</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-white truncate">{{ $order->product->name }}</p>
                            <p class="text-xs text-gray-500">{{ $order->seller->name }} • {{ $order->created_at->diffForHumans() }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-white">${{ number_format($order->total_price, 2) }}</p>
                            @php
                                $orderStatusClass = match($order->status) {
                                    'pending' => 'bg-yellow-500/20 text-yellow-400',
                                    'confirmed' => 'bg-accent-blue/20 text-accent-blue',
                                    'shipped' => 'bg-accent-purple/20 text-accent-purple',
                                    'delivered' => 'bg-primary-600/20 text-primary-400',
                                    default => 'bg-gray-500/20 text-gray-400'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs {{ $orderStatusClass }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ ucfirst($order->status) }}
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-12">
                <span class="material-symbols-outlined text-4xl text-gray-600 mb-3">inbox</span>
                <p class="text-gray-500 mb-4">No orders yet</p>
                <a href="{{ route('buyer.marketplace') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-primary-600 text-white text-sm">
                    <span class="material-symbols-outlined">shopping_basket</span>
                    Start Shopping
                </a>
            </div>
        @endif
    </div>

    <!-- Recommendations -->
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Recommended</h3>
            <a href="{{ route('buyer.marketplace') }}" class="text-sm text-primary-400 hover:text-primary-300">Browse</a>
        </div>
        <div class="space-y-4">
            @foreach($recommendations->take(4) as $product)
                <a href="{{ route('buyer.marketplace.show', $product) }}" class="flex items-center gap-4 p-3 rounded-xl bg-dark-900/50 hover:bg-dark-700 transition-colors">
                    <div class="w-14 h-14 rounded-lg bg-dark-700 flex items-center justify-center overflow-hidden">
                        @if($product->first_image)
                            <img src="{{ $product->first_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-gray-600">image</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-white truncate">{{ $product->name }}</p>
                        <p class="text-xs text-gray-500">{{ $product->location }}</p>
                    </div>
                    <p class="text-sm font-semibold text-primary-400">${{ number_format($product->price, 2) }}</p>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endsection
