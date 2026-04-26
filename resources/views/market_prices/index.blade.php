@extends('layouts.dashboard')

@section('title', 'Market Prices')
@section('page-title', 'Market Prices')

@section('page-content')
<!-- Filters -->
<div class="p-4 rounded-2xl bg-dark-800 border border-dark-700 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4">
        <select name="country" class="px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
            <option value="">All Countries</option>
            @foreach($countries as $country)
                <option value="{{ $country }}" {{ request('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
            @endforeach
        </select>
        <select name="category" class="px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium transition-all">
            Filter
        </button>
    </form>
</div>

<!-- Prices Table -->
<div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden">
    <table class="w-full">
        <thead>
            <tr class="border-b border-dark-700">
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Product</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Market</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Price (USD)</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Trend</th>
                <th class="px-6 py-4 text-left text-sm font-medium text-gray-400">Updated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($prices as $price)
                <tr class="border-b border-dark-700 last:border-0 hover:bg-dark-700/30 transition-colors">
                    <td class="px-6 py-4">
                        <div>
                            <p class="font-medium text-white">{{ $price->product_name }}</p>
                            <p class="text-xs text-gray-500">{{ $price->category }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <div>
                            <p class="text-white">{{ $price->market_name }}</p>
                            <p class="text-xs text-gray-500">{{ $price->country }}</p>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-lg font-semibold text-white">${{ number_format($price->price_usd, 2) }}</span>
                        <span class="text-xs text-gray-500">/{{ $price->price_unit ?? 'kg' }}</span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2 {{ $price->trend_color }}">
                            <span class="material-symbols-outlined text-sm">{{ $price->trend_icon }}</span>
                            <span class="font-medium">{{ $price->price_change_24h > 0 ? '+' : '' }}{{ number_format($price->price_change_24h, 1) }}%</span>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="text-sm text-gray-500">{{ $price->price_date->diffForHumans() }}</span>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="mt-6">
    {{ $prices->links() }}
</div>
@endsection
