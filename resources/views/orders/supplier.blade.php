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
                            <h3 class="font-semibold text-white">{{ $order->product->name ?? 'Unknown Product' }}</h3>
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
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-dark-700">
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Buyer</p>
                        <p class="text-sm text-white font-medium">{{ $order->buyer->name ?? 'Unknown' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Quantity</p>
                        <p class="text-sm text-white font-medium">{{ $order->quantity ?? 'N/A' }} units</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500 mb-1">Total</p>
                        <p class="text-lg font-semibold text-white">${{ number_format($order->total_price, 2) }}</p>
                    </div>
                </div>

                @if($order->status === 'pending')
                    <div class="flex gap-2 mt-4 pt-4 border-t border-dark-700">
                        <form method="POST" action="{{ route('supplier.orders.status', $order) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-primary-600 text-white text-sm hover:bg-primary-500 transition-colors">
                                Confirm Order
                            </button>
                        </form>
                        <form method="POST" action="{{ route('supplier.orders.status', $order) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-dark-700 text-gray-400 text-sm hover:bg-dark-600 transition-colors">
                                Cancel
                            </button>
                        </form>
                    </div>
                @elseif($order->status === 'confirmed')
                    <div class="flex gap-2 mt-4 pt-4 border-t border-dark-700">
                        <form method="POST" action="{{ route('supplier.orders.status', $order) }}" class="inline">
                            @csrf
                            @method('PUT')
                            <input type="hidden" name="status" value="shipped">
                            <button type="submit" class="px-4 py-2 rounded-lg bg-accent-blue text-white text-sm hover:bg-accent-blue/80 transition-colors">
                                Mark as Shipped
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $orders->links() }}
    </div>
@else
    <div class="text-center py-12">
        <div class="w-20 h-20 rounded-full bg-dark-800 border border-dark-700 flex items-center justify-center mx-auto mb-4">
            <span class="material-symbols-outlined text-4xl text-gray-600">inbox</span>
        </div>
        <h3 class="text-lg font-semibold text-white mb-2">No orders yet</h3>
        <p class="text-gray-500 mb-4">Your supply orders will appear here</p>
    </div>
@endif
@endsection
