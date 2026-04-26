@extends('layouts.dashboard')

@section('title', $product->name)
@section('page-title', 'Product Details')

@section('page-content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
    <!-- Product Images -->
    <div class="space-y-4">
        <div class="aspect-square rounded-2xl bg-dark-800 border border-dark-700 overflow-hidden">
            @if($product->first_image)
                <img src="{{ $product->first_image }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center">
                    <span class="material-symbols-outlined text-6xl text-gray-600">image</span>
                </div>
            @endif
        </div>
        @if(count($product->images ?? []) > 1)
            <div class="grid grid-cols-4 gap-2">
                @foreach(array_slice($product->images, 0, 4) as $image)
                    <div class="aspect-square rounded-xl bg-dark-800 border border-dark-700 overflow-hidden">
                        <img src="{{ $image }}" alt="" class="w-full h-full object-cover">
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Product Info -->
    <div class="space-y-6">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <span class="px-3 py-1 rounded-full bg-primary-600/20 text-primary-400 text-sm">{{ $product->category }}</span>
                @if($product->is_organic)
                    <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-sm flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">eco</span> Organic
                    </span>
                @endif
            </div>
            <h1 class="text-3xl font-bold text-white mb-2">{{ $product->name }}</h1>
            <p class="text-gray-400 flex items-center gap-2">
                <span class="material-symbols-outlined text-sm">location_on</span>
                {{ $product->location }}
            </p>
        </div>

        <div class="p-4 rounded-xl bg-dark-800 border border-dark-700">
            <div class="flex items-baseline gap-2">
                <span class="text-4xl font-bold text-primary-400">${{ number_format($product->price, 2) }}</span>
                <span class="text-gray-500">per {{ $product->price_unit }}</span>
            </div>
            <p class="text-sm text-gray-500 mt-2">
                {{ number_format($product->quantity, 0) }} {{ $product->quantity_unit }} available
            </p>
        </div>

        @if($marketPrice)
            <div class="p-4 rounded-xl bg-dark-800/50 border border-dark-700">
                <div class="flex items-center gap-2 mb-2">
                    <span class="material-symbols-outlined text-accent-blue">trending_up</span>
                    <span class="text-sm font-medium text-gray-300">Market Price</span>
                </div>
                <p class="text-sm text-gray-400">
                    Current market average: <span class="text-white font-medium">${{ number_format($marketPrice->price_usd, 2) }}</span>
                    in {{ $marketPrice->market_name }}
                </p>
            </div>
        @endif

        <div>
            <h3 class="font-semibold text-white mb-2">Description</h3>
            <p class="text-gray-400 leading-relaxed">{{ $product->description ?? 'No description available.' }}</p>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div class="p-3 rounded-xl bg-dark-800 border border-dark-700">
                <span class="text-gray-500">Harvest Date</span>
                <p class="text-white font-medium">{{ $product->harvest_date?->format('M d, Y') ?? 'N/A' }}</p>
            </div>
            <div class="p-3 rounded-xl bg-dark-800 border border-dark-700">
                <span class="text-gray-500">Best Before</span>
                <p class="text-white font-medium">{{ $product->expiry_date?->format('M d, Y') ?? 'N/A' }}</p>
            </div>
        </div>

        <!-- Seller Info -->
        <div class="p-4 rounded-xl bg-dark-800 border border-dark-700">
            <div class="flex items-center gap-4">
                <img src="{{ $product->user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($product->user->name) . '&background=10b981&color=fff' }}"
                     alt="{{ $product->user->name }}"
                     class="w-12 h-12 rounded-xl object-cover">
                <div>
                    <p class="font-medium text-white">{{ $product->user->name }}</p>
                    <p class="text-sm text-gray-500 capitalize">{{ $product->user->role }}</p>
                </div>
                <a href="{{ route('messages.conversation', $product->user) }}" class="ml-auto p-2 rounded-lg bg-primary-600/20 text-primary-400 hover:bg-primary-600/30 transition-colors">
                    <span class="material-symbols-outlined">chat</span>
                </a>
            </div>
        </div>

        <!-- Order Form -->
        <form method="POST" action="{{ route('buyer.orders.store') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Quantity ({{ $product->quantity_unit }})</label>
                <input type="number" name="quantity" min="1" max="{{ $product->quantity }}" value="1" required
                       class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-300 mb-2">Delivery Address</label>
                <textarea name="delivery_address" rows="3" required
                          class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none"
                          placeholder="Enter your delivery address..."></textarea>
            </div>

            <button type="submit" class="w-full py-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center justify-center gap-2">
                <span class="material-symbols-outlined">shopping_cart</span>
                Place Order
            </button>
        </form>
    </div>
</div>

<!-- Related Products -->
@if($related->count() > 0)
    <div class="mt-12">
        <h3 class="text-xl font-semibold text-white mb-6">Related Products</h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($related as $item)
                <a href="{{ route('buyer.marketplace.show', $item) }}" class="group bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden hover:border-primary-600/50 transition-all">
                    <div class="aspect-square bg-dark-700 relative overflow-hidden">
                        @if($item->first_image)
                            <img src="{{ $item->first_image }}" alt="{{ $item->name }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <span class="material-symbols-outlined text-4xl text-gray-600">image</span>
                            </div>
                        @endif
                    </div>
                    <div class="p-4">
                        <h4 class="font-medium text-white truncate">{{ $item->name }}</h4>
                        <p class="text-primary-400 font-semibold mt-1">${{ number_format($item->price, 2) }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
