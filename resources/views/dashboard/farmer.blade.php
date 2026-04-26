@extends('layouts.dashboard')

@section('title', 'Farmer Dashboard')
@section('page-title', 'Dashboard')

@section('page-content')
<!-- Top Widgets Row - Velonic Style -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4 items-stretch">
    <!-- Weather Widget -->
    <div class="p-4 rounded-2xl bg-dark-800 border border-dark-700 relative overflow-hidden flex flex-col">
        <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-br from-yellow-500/10 to-orange-500/10 rounded-full blur-2xl -mr-10 -mt-10"></div>
        <div class="relative flex-1 flex flex-col justify-between">
            <div class="flex items-center justify-between mb-2">
                <div>
                    <p class="text-sm text-gray-400">Arusha, Tanzania</p>
                    <p class="text-xs text-gray-500">{{ now()->format('l, M d') }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
                    <span class="material-symbols-outlined text-white text-xl">wb_sunny</span>
                </div>
            </div>
            <div class="flex items-end gap-1 mb-2">
                <span class="text-4xl font-bold text-white">29°</span>
                <span class="text-gray-400 text-sm mb-1">C</span>
            </div>
            <div class="flex items-center gap-3 text-xs text-gray-400">
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs">air</span> 12km/h</span>
                <span class="flex items-center gap-1"><span class="material-symbols-outlined text-xs">water_drop</span> 65%</span>
            </div>
        </div>
    </div>

    <!-- Production Stats -->
    <div class="p-4 rounded-2xl bg-dark-800 border border-dark-700 flex flex-col">
        <div class="flex items-center justify-between mb-3">
            <h3 class="text-base font-semibold text-white">Crop Production</h3>
            <span class="text-xs text-gray-500">This Season</span>
        </div>
        <div class="space-y-3 flex-1 flex flex-col justify-around">
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-400">Maize</span>
                    <span class="text-xs text-white font-medium">2,450 kg</span>
                </div>
                <div class="h-1.5 rounded-full bg-dark-700 overflow-hidden">
                    <div class="h-full bg-primary-500 rounded-full" style="width: 85%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-400">Tomatoes</span>
                    <span class="text-xs text-white font-medium">1,200 kg</span>
                </div>
                <div class="h-1.5 rounded-full bg-dark-700 overflow-hidden">
                    <div class="h-full bg-accent-orange rounded-full" style="width: 65%"></div>
                </div>
            </div>
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs text-gray-400">Green Beans</span>
                    <span class="text-xs text-white font-medium">800 kg</span>
                </div>
                <div class="h-1.5 rounded-full bg-dark-700 overflow-hidden">
                    <div class="h-full bg-accent-purple rounded-full" style="width: 45%"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Farm Image Card -->
    <div class="rounded-2xl bg-dark-800 border border-dark-700 overflow-hidden relative flex flex-col">
        <img src="https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=400&h=300&fit=crop" alt="Farm" class="w-full h-24 object-cover">
        <div class="p-3 flex-1 flex flex-col justify-between">
            <div>
                <h3 class="font-semibold text-white text-sm mb-0.5">My Farm</h3>
                <p class="text-xs text-gray-400">Arusha • 5 acres</p>
            </div>
            <button class="w-full py-1.5 rounded-lg bg-primary-600/20 text-primary-400 text-xs hover:bg-primary-600/30 transition-colors mt-2">
                + Add New Plot
            </button>
        </div>
    </div>
</div>

<!-- Stats Cards Row -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-4">
    <div class="p-4 rounded-xl bg-dark-800 border border-dark-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-primary-600/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-primary-400">inventory_2</span>
            </div>
            <span class="text-xs text-gray-400">Products</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['products'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Active listings</p>
    </div>
    <div class="p-4 rounded-xl bg-dark-800 border border-dark-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-accent-blue/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-blue">receipt_long</span>
            </div>
            <span class="text-xs text-gray-400">Orders</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['orders'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Total received</p>
    </div>
    <div class="p-4 rounded-xl bg-dark-800 border border-dark-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-accent-orange/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-orange">payments</span>
            </div>
            <span class="text-xs text-gray-400">Revenue</span>
        </div>
        <p class="text-2xl font-bold text-white">${{ number_format($stats['revenue'], 0) }}</p>
        <p class="text-xs text-gray-500 mt-1">Total earnings</p>
    </div>
    <div class="p-4 rounded-xl bg-dark-800 border border-dark-700">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-10 h-10 rounded-lg bg-accent-purple/20 flex items-center justify-center">
                <span class="material-symbols-outlined text-accent-purple">chat</span>
            </div>
            <span class="text-xs text-gray-400">Messages</span>
        </div>
        <p class="text-2xl font-bold text-white">{{ $stats['messages'] }}</p>
        <p class="text-xs text-gray-500 mt-1">Unread</p>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
    <!-- Left Column - 2/3 width -->
    <div class="lg:col-span-2 space-y-4">
        <!-- Production Chart -->
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-white">Production Overview</h3>
                    <p class="text-xs text-gray-500">Monthly yield comparison</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="flex items-center gap-1 text-xs text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-primary-500"></span> This Year
                    </span>
                    <span class="flex items-center gap-1 text-xs text-gray-400">
                        <span class="w-2 h-2 rounded-full bg-dark-600"></span> Last Year
                    </span>
                </div>
            </div>
            <!-- Simple Bar Chart -->
            <div class="flex items-end justify-between gap-2 h-40">
                @foreach(['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'] as $month)
                    @php
                        $currentHeight = rand(30, 90);
                        $lastHeight = rand(20, 80);
                    @endphp
                    <div class="flex-1 flex flex-col items-center gap-1">
                        <div class="w-full flex items-end gap-0.5 h-32">
                            <div class="flex-1 rounded-t bg-dark-600" style="height: {{ $lastHeight }}%"></div>
                            <div class="flex-1 rounded-t bg-primary-500" style="height: {{ $currentHeight }}%"></div>
                        </div>
                        <span class="text-xs text-gray-500">{{ $month }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- AI Insights -->
        <div class="p-5 rounded-2xl bg-gradient-to-r from-primary-900/20 to-dark-800 border border-primary-700/30">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-primary-600/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-primary-400">psychology</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">AI Insights</h3>
                    <p class="text-xs text-gray-500">Powered by AgroSphere AI</p>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="p-4 rounded-xl bg-dark-900/50 border border-primary-600/20">
                    <span class="material-symbols-outlined text-accent-blue text-sm mb-2">trending_up</span>
                    <p class="text-sm text-gray-300">Maize prices predicted to rise <span class="text-primary-400 font-semibold">12%</span> next week</p>
                </div>
                <div class="p-4 rounded-xl bg-dark-900/50 border border-accent-orange/20">
                    <span class="material-symbols-outlined text-accent-orange text-sm mb-2">calendar_today</span>
                    <p class="text-sm text-gray-300">Optimal harvest window for tomatoes: <span class="text-accent-orange font-semibold">within 5 days</span></p>
                </div>
                <div class="p-4 rounded-xl bg-dark-900/50 border border-accent-purple/20">
                    <span class="material-symbols-outlined text-accent-purple text-sm mb-2">local_fire_department</span>
                    <p class="text-sm text-gray-300">Demand for green beans up <span class="text-accent-purple font-semibold">25%</span> in your region</p>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Recent Orders</h3>
                <a href="{{ route('farmer.orders') }}" class="text-sm text-primary-400 hover:text-primary-300 flex items-center gap-1">
                    View all <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>
            </div>
            @if($recentOrders->count() > 0)
                <div class="space-y-3">
                    @foreach($recentOrders as $order)
                        <div class="flex items-center gap-4 p-3 rounded-xl bg-dark-900/50">
                            <div class="w-10 h-10 rounded-lg bg-primary-600/20 flex items-center justify-center">
                                <span class="material-symbols-outlined text-primary-400 text-sm">shopping_bag</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-white truncate">{{ $order->product->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->buyer->name }} • {{ $order->created_at->diffForHumans() }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-semibold text-white">${{ number_format($order->total_price, 2) }}</p>
                                @php
                                    $statusClass = match($order->status) {
                                        'pending' => 'bg-yellow-500/20 text-yellow-400',
                                        'confirmed' => 'bg-accent-blue/20 text-accent-blue',
                                        'shipped' => 'bg-accent-purple/20 text-accent-purple',
                                        'delivered' => 'bg-primary-600/20 text-primary-400',
                                        default => 'bg-gray-500/20 text-gray-400'
                                    };
                                @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs {{ $statusClass }}">
                                    <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <span class="material-symbols-outlined text-3xl text-gray-600 mb-2">inbox</span>
                    <p class="text-gray-500 text-sm">No orders yet</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Right Column - 1/3 width -->
    <div class="space-y-4">
        <!-- Quick Actions -->
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4">Quick Actions</h3>
            <div class="space-y-2">
                <a href="{{ route('farmer.products.create') }}" class="flex items-center gap-3 p-3 rounded-xl bg-primary-600 text-white hover:bg-primary-700 transition-colors">
                    <span class="material-symbols-outlined">add</span>
                    <span class="text-sm font-medium">Add New Product</span>
                </a>
                <a href="{{ route('market.prices') }}" class="flex items-center gap-3 p-3 rounded-xl bg-dark-700 text-gray-300 hover:text-white hover:bg-dark-600 transition-colors">
                    <span class="material-symbols-outlined">trending_up</span>
                    <span class="text-sm">Check Market Prices</span>
                </a>
                <a href="{{ route('messages') }}" class="flex items-center gap-3 p-3 rounded-xl bg-dark-700 text-gray-300 hover:text-white hover:bg-dark-600 transition-colors">
                    <span class="material-symbols-outlined">chat</span>
                    <span class="text-sm">Messages</span>
                </a>
            </div>
        </div>

        <!-- Market Prices -->
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-white">Market Prices</h3>
                <a href="{{ route('market.prices') }}" class="text-sm text-primary-400 hover:text-primary-300">View all</a>
            </div>
            <div class="space-y-3">
                @foreach($marketPrices->take(5) as $price)
                    <div class="flex items-center justify-between p-3 rounded-xl bg-dark-900/50">
                        <div>
                            <p class="text-sm font-medium text-white">{{ $price->product_name }}</p>
                            <p class="text-xs text-gray-500">{{ $price->market_name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-sm font-semibold text-white">${{ number_format($price->price_usd, 2) }}</p>
                            <span class="text-xs {{ $price->trend_color }}">
                                {{ $price->price_change_24h > 0 ? '+' : '' }}{{ number_format($price->price_change_24h, 1) }}%
                            </span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Blockchain Activity Feed -->
        @if(isset($blockchainActivities) && count($blockchainActivities) > 0)
            <x-blockchain-activity-feed :activities="$blockchainActivities" />
        @endif

        <!-- Farm Tips -->
        <div class="p-5 rounded-2xl bg-gradient-to-br from-primary-900/30 to-dark-800 border border-primary-700/30">
            <div class="flex items-center gap-2 mb-3">
                <span class="material-symbols-outlined text-primary-400">tips_and_updates</span>
                <h3 class="font-semibold text-white">Daily Tip</h3>
            </div>
            <p class="text-sm text-gray-300">Best time to water your crops is early morning (6-8 AM) to minimize evaporation and fungal diseases.</p>
        </div>
    </div>
</div>
@endsection
