@extends('layouts.dashboard')

@section('title', 'Inventory')
@section('page-title', 'Inventory Management')

@section('page-content')
<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">inventory</span>
            </div>
            <span class="text-xs text-gray-500">Total Stock</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ number_format($inventory, 0) }}</div>
        <p class="text-sm text-gray-500 mt-1">Units</p>
    </div>

    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-4">
            <div class="w-12 h-12 rounded-xl bg-yellow-500/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-yellow-400">warning</span>
            </div>
            <span class="text-xs text-gray-500">Low Stock</span>
        </div>
        <div class="text-3xl font-bold text-white">{{ $lowStock->count() }}</div>
        <p class="text-sm text-gray-500 mt-1">Products</p>
    </div>
</div>

<!-- Low Stock Alert -->
@if($lowStock->count() > 0)
    <div class="mb-6 p-4 rounded-xl bg-yellow-900/20 border border-yellow-700/50">
        <div class="flex items-center gap-2 mb-2">
            <span class="material-symbols-outlined text-yellow-400">warning</span>
            <span class="font-medium text-yellow-400">Low Stock Alert</span>
        </div>
        <p class="text-sm text-yellow-200/70">The following products are running low on stock:</p>
        <ul class="mt-2 space-y-1">
            @foreach($lowStock as $product)
                <li class="text-sm text-yellow-200/70">• {{ $product->name }} ({{ number_format($product->quantity, 0) }} {{ $product->quantity_unit }} remaining)</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Inventory Table -->
<div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-dark-700">
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Product</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Category</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Stock Level</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach(auth()->user()->products()->latest()->get() as $product)
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
                        <span class="px-3 py-1 rounded-full bg-dark-700 text-gray-300 text-sm">{{ $product->category }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <div class="w-24 h-2 rounded-full bg-dark-700 overflow-hidden">
                                @php
                                    $percentage = min(100, ($product->quantity / 100) * 100);
                                    $color = $percentage < 20 ? 'bg-red-500' : ($percentage < 50 ? 'bg-yellow-500' : 'bg-primary-500');
                                @endphp
                                <div class="h-full {{ $color }}" style="width: {{ $percentage }}%"></div>
                            </div>
                            <span class="text-sm text-gray-400">{{ number_format($product->quantity, 0) }}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-sm
                            @if($product->quantity < 10) bg-red-500/20 text-red-400
                            @elseif($product->quantity < 50) bg-yellow-500/20 text-yellow-400
                            @else bg-primary-600/20 text-primary-400 @endif">
                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                            @if($product->quantity < 10) Low Stock
                            @elseif($product->quantity < 50) Medium
                            @else In Stock @endif
                        </span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
