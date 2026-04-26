@extends('layouts.dashboard')

@section('title', 'Products Management')
@section('page-title', 'Products')

@section('page-content')
<div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-dark-700">
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Product</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Seller</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Price</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Stock</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr class="border-b border-dark-700 last:border-0 hover:bg-dark-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-lg bg-dark-700 flex items-center justify-center overflow-hidden">
                                @if($product->first_image)
                                    <img src="{{ $product->first_image }}" alt="" class="w-full h-full object-cover">
                                @else
                                    <span class="material-symbols-outlined text-gray-600">image</span>
                                @endif
                            </div>
                            <span class="font-medium text-white">{{ $product->name }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400">{{ $product->user->name }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-white">${{ number_format($product->price, 2) }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-gray-400">{{ number_format($product->quantity, 0) }} {{ $product->quantity_unit }}</span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $productStatusClass = match($product->status) {
                                'available' => 'bg-primary-600/20 text-primary-400',
                                'sold' => 'bg-accent-blue/20 text-accent-blue',
                                'reserved' => 'bg-yellow-500/20 text-yellow-400',
                                default => 'bg-gray-500/20 text-gray-400'
                            };
                        @endphp
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm {{ $productStatusClass }}">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            {{ ucfirst($product->status) }}
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $products->links() }}
</div>
@endsection
