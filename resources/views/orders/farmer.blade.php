@extends('layouts.dashboard')

@section('title', 'Orders')
@section('page-title', 'Orders')

@section('page-content')
@if($orders->count() > 0)
    <div class="space-y-4">
        @foreach($orders as $order)
            <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-400">shopping_bag</span>
                        </div>
                        <div>
                            <h3 class="font-semibold text-white">{{ $order->product->name }}</h3>
                            <p class="text-sm text-gray-500">Order #{{ $order->id }} • {{ $order->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        @php
                            $orderStatusClass = match($order->status) {
                                'pending' => 'bg-yellow-500/20 text-yellow-400',
                                'confirmed' => 'bg-accent-blue/20 text-accent-blue',
                                'shipped' => 'bg-accent-purple/20 text-accent-purple',
                                'delivered' => 'bg-primary-600/20 text-primary-400',
                                'cancelled' => 'bg-red-500/20 text-red-400',
                                default => 'bg-gray-500/20 text-gray-400'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-medium {{ $orderStatusClass }}">
                            <span class="w-2 h-2 rounded-full bg-current"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                        @if($order->status === 'pending')
                            <form method="POST" action="{{ route('farmer.orders.status', $order) }}" class="inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="confirmed">
                                <button type="submit" class="p-2 rounded-lg bg-primary-600/20 text-primary-400 hover:bg-primary-600/30 transition-colors">
                                    <span class="material-symbols-outlined">check</span>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-dark-700">
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Buyer</p>
                        <p class="text-white font-medium">{{ $order->buyer->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Quantity</p>
                        <p class="text-white font-medium">{{ number_format($order->quantity, 0) }} {{ $order->product->quantity_unit }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500 mb-1">Total</p>
                        <p class="text-white font-medium">${{ number_format($order->total_price, 2) }}</p>
                    </div>
                </div>

                @if($order->delivery_address)
                    <div class="mt-4 pt-4 border-t border-dark-700">
                        <p class="text-sm text-gray-500 flex items-center gap-2">
                            <span class="material-symbols-outlined text-accent-blue">location_on</span>
                            Delivery: {{ $order->delivery_address }}
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@else
    <div class="text-center py-20 bg-dark-800 border border-dark-700 rounded-2xl relative overflow-hidden">
        <!-- Subtle gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 via-transparent to-transparent"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-600/20 to-dark-700 flex items-center justify-center mx-auto mb-6 border border-primary-600/30 shadow-lg shadow-primary-600/10">
                <span class="material-symbols-outlined text-5xl text-primary-400">inbox</span>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">No orders yet</h3>
            <p class="text-gray-400 mb-8 max-w-sm mx-auto">Orders from buyers will appear here once they purchase your products</p>
            <a href="{{ route('farmer.products') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 transform hover:-translate-y-0.5">
                <span class="material-symbols-outlined">inventory_2</span>
                Manage Products
            </a>
        </div>
    </div>
@endif
@endsection
