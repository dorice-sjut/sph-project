@extends('layouts.dashboard')

@section('title', 'Marketplace')
@section('page-title', 'Marketplace')

@section('page-content')
<!-- Search & Filters -->
<div class="p-4 rounded-2xl bg-dark-800 border border-dark-700 mb-6">
    <form method="GET" class="flex flex-col md:flex-row gap-4" id="marketplace-filter">
        <div class="flex-1 relative">
            <span class="material-symbols-outlined absolute left-4 top-3 text-gray-500">search</span>
            <input type="text" name="search" value="{{ request('search') }}"
                   class="w-full pl-12 pr-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white placeholder-gray-500 focus:border-primary-500 focus:outline-none"
                   placeholder="Search products...">
        </div>
        <select name="category" onchange="this.form.submit()" class="px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none cursor-pointer">
            <option value="">All Categories</option>
            @foreach($categories as $category)
                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-6 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-medium transition-all">
            Search
        </button>
    </form>
</div>

<!-- Market Insights -->
<div class="mb-6 p-4 rounded-2xl bg-dark-800/50 border border-dark-700">
    <div class="flex items-center gap-2 mb-3">
        <span class="material-symbols-outlined text-primary-400">trending_up</span>
        <span class="text-sm font-medium text-gray-300">Market Insights</span>
    </div>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($marketPrices as $price)
            <div class="p-3 rounded-xl bg-dark-900/50">
                <p class="text-xs text-gray-500 mb-1">{{ $price->product_name }}</p>
                <p class="text-lg font-semibold text-white">${{ number_format($price->price_usd, 2) }}</p>
                <span class="text-xs {{ $price->trend_color }}">
                    {{ $price->price_change_24h > 0 ? '+' : '' }}{{ number_format($price->price_change_24h, 1) }}%
                </span>
            </div>
        @endforeach
    </div>
</div>

<!-- Products Grid -->
@if($products->count() > 0)
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($products as $product)
            <a href="{{ route('buyer.marketplace.show', $product) }}" class="group bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="aspect-square bg-dark-700 relative overflow-hidden">
                    @if($product->first_image)
                        <img src="{{ $product->first_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="material-symbols-outlined text-4xl text-gray-600">image</span>
                        </div>
                    @endif
                    @if($product->is_organic)
                        <span class="absolute top-3 left-3 px-2 py-1 rounded-lg bg-primary-600/90 text-white text-xs font-medium">
                            Organic
                        </span>
                    @endif
                </div>
                <div class="p-4">
                    <h3 class="font-semibold text-white mb-1 truncate">{{ $product->name }}</h3>
                    <p class="text-sm text-gray-500 mb-3 flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">location_on</span>
                        {{ $product->location }}
                    </p>
                    <div class="flex items-center justify-between">
                        <span class="text-lg font-bold text-primary-400">${{ number_format($product->price, 2) }}<span class="text-sm text-gray-500">/{{ $product->price_unit }}</span></span>
                        <span class="text-sm text-gray-500">{{ number_format($product->quantity, 0) }} {{ $product->quantity_unit }}</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-2">by {{ $product->user->name }}</p>
                </div>
            </a>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $products->links() }}
    </div>
@else
    <div class="text-center py-20 bg-dark-800 border border-dark-700 rounded-2xl relative overflow-hidden">
        <!-- Subtle gradient background -->
        <div class="absolute inset-0 bg-gradient-to-br from-primary-900/10 via-transparent to-transparent"></div>
        
        <div class="relative z-10">
            <div class="w-24 h-24 rounded-2xl bg-gradient-to-br from-primary-600/20 to-dark-700 flex items-center justify-center mx-auto mb-6 border border-primary-600/30 shadow-lg shadow-primary-600/10">
                <span class="material-symbols-outlined text-5xl text-primary-400">search_off</span>
            </div>
            <h3 class="text-xl font-semibold text-white mb-3">No products found</h3>
            <p class="text-gray-400 mb-8 max-w-sm mx-auto">Try adjusting your search or category filters to find what you're looking for</p>
            <button onclick="window.location.reload()" class="inline-flex items-center gap-2 px-8 py-3.5 rounded-xl bg-gradient-to-r from-dark-700 to-dark-800 text-white font-medium hover:from-dark-600 hover:to-dark-700 transition-all border border-dark-600">
                <span class="material-symbols-outlined">refresh</span>
                Clear Filters
            </button>
        </div>
    </div>
@endif
@endsection
