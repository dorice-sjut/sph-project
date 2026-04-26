@extends('layouts.dashboard')

@section('title', 'My Products')
@section('page-title', 'My Products')

@section('page-content')
<div class="flex items-center justify-between mb-6">
    <div class="flex items-center gap-4">
        <div class="relative">
            <span class="material-symbols-outlined absolute left-4 top-3 text-gray-500">search</span>
            <input type="text" placeholder="Search products..."
                   class="pl-12 pr-4 py-3 rounded-xl bg-dark-800 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none w-64">
        </div>
    </div>
    <a href="{{ route('farmer.products.create') }}" class="px-5 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium transition-all flex items-center gap-2">
        <span class="material-symbols-outlined">add</span>
        Add Product
    </a>
</div>

@if($products->count() > 0)
    <div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden">
        <table class="w-full">
            <thead>
                <tr class="border-b border-dark-700">
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Product</th>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Category</th>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Price</th>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Stock</th>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Blockchain</th>
                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-400">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($products as $product)
                    <tr class="border-b border-dark-700 last:border-0 hover:bg-dark-700/30 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 rounded-lg bg-dark-700 flex items-center justify-center overflow-hidden">
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
                            <span class="px-3 py-1 rounded-full bg-dark-700 text-gray-300 text-sm">{{ $product->category }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white font-medium">${{ number_format($product->price, 2) }}</span>
                            <span class="text-gray-500 text-sm">/{{ $product->price_unit }}</span>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-white">{{ number_format($product->quantity, 0) }} {{ $product->quantity_unit }}</span>
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
                        <td class="px-6 py-4">
                            @if($product->is_blockchain_verified)
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-xs">
                                        <span class="material-symbols-outlined text-[10px]">verified</span>
                                        Verified
                                    </span>
                                </div>
                                <div class="mt-1 text-[10px] font-mono text-slate-500">
                                    {{ substr($product->batch_id, 0, 12) }}...
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full bg-slate-700/50 text-slate-400 text-xs">
                                    <span class="material-symbols-outlined text-[10px]">unverified</span>
                                    Not Verified
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ route('farmer.products.edit', $product) }}" class="p-2 rounded-lg bg-dark-700 text-gray-400 hover:text-white hover:bg-dark-600 transition-colors">
                                    <span class="material-symbols-outlined text-sm">edit</span>
                                </a>
                                <form method="POST" action="{{ route('farmer.products.destroy', $product) }}" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 rounded-lg bg-dark-700 text-gray-400 hover:text-red-400 hover:bg-red-500/10 transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $products->links() }}
    </div>
@else
    <div class="text-center py-20 bg-dark-800 border border-dark-700 rounded-2xl relative overflow-hidden">
        <!-- Subtle gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 via-transparent to-transparent"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-600/20 to-dark-700 flex items-center justify-center mx-auto mb-6 border border-primary-600/30 shadow-lg shadow-primary-600/10">
                <span class="material-symbols-outlined text-5xl text-primary-400">inventory_2</span>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">No products yet</h3>
            <p class="text-gray-400 mb-8 max-w-sm mx-auto">Start listing your agricultural products and reach buyers across the region</p>
            <a href="{{ route('farmer.products.create') }}" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20 hover:shadow-primary-600/30 transform hover:-translate-y-0.5">
                <span class="material-symbols-outlined">add</span>
                Add Your First Product
            </a>
        </div>
    </div>
@endif
@endsection
