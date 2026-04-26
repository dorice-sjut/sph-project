@extends('layouts.dashboard')

@section('title', 'Orders Management')
@section('page-title', 'Orders')

@section('page-content')
<div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-dark-700">
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Order ID</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Product</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Buyer</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Seller</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Total</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="border-b border-dark-700 last:border-0 hover:bg-dark-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <span class="text-gray-400">#{{ $order->id }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-white">{{ $order->product->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400">{{ $order->buyer->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400">{{ $order->seller->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="font-medium text-white">${{ number_format($order->total_price, 2) }}</span>
                    </td>
                    <td class="px-6 py-4">
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
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm {{ $orderStatusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $orders->links() }}
</div>
@endsection
