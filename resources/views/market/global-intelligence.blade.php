@extends('layouts.dashboard')

@section('page-title', 'Global Market Intelligence')

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-600 to-purple-700 flex items-center justify-center shadow-lg shadow-indigo-600/20">
                    <span class="material-symbols-outlined text-white text-2xl">public</span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white">Global Market Intelligence</h1>
                    <p class="text-gray-400">Real-time crop prices across Tanzania, Africa & Global markets</p>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-400">Last Updated</div>
                <div class="text-white font-medium">{{ $insights['last_updated'] }}</div>
            </div>
        </div>
    </div>

    <!-- Best Market to Sell Card -->
    @if($insights['best_opportunity'])
    <div class="mb-8">
        <div class="bg-gradient-to-r from-amber-900/40 via-orange-900/40 to-red-900/40 border border-amber-700/50 rounded-2xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-500/20 to-orange-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-amber-400 text-2xl">emoji_events</span>
                    <h2 class="text-xl font-bold text-white">Best Market to Sell</h2>
                    <span class="px-3 py-1 rounded-full bg-amber-500/20 text-amber-400 text-xs font-medium">Top Opportunity</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="flex items-center gap-4">
                        <div class="text-4xl">{{ $insights['best_opportunity']['icon'] }}</div>
                        <div>
                            <div class="text-lg font-semibold text-white">{{ $insights['best_opportunity']['crop'] }}</div>
                            <div class="text-sm text-gray-400">Highest export potential</div>
                        </div>
                    </div>

                    <div class="bg-dark-900/50 rounded-xl p-4">
                        <div class="text-sm text-gray-400 mb-1">Tanzania Price</div>
                        <div class="text-xl font-bold text-white">${{ number_format($insights['best_opportunity']['local_price']) }}<span class="text-sm font-normal text-gray-500">/ton</span></div>
                    </div>

                    <div class="bg-dark-900/50 rounded-xl p-4">
                        <div class="text-sm text-gray-400 mb-1">Global Price</div>
                        <div class="text-xl font-bold text-green-400">${{ number_format($insights['best_opportunity']['export_price']) }}<span class="text-sm font-normal text-gray-500">/ton</span></div>
                    </div>

                    <div class="bg-gradient-to-r from-green-600/20 to-emerald-600/20 rounded-xl p-4 border border-green-500/30">
                        <div class="text-sm text-gray-400 mb-1">Potential Gain</div>
                        <div class="text-2xl font-bold text-green-400">+{{ $insights['best_opportunity']['percent_gain'] }}%</div>
                        <div class="text-xs text-green-500">${{ number_format($insights['best_opportunity']['difference']) }} more per ton</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Market Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-green-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-green-400">trending_up</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">{{ $insights['trending_count'] }}</div>
                    <div class="text-sm text-gray-400">Crops Trending Up</div>
                </div>
            </div>
        </div>

        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-400">local_fire_department</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">{{ $insights['high_demand_count'] }}</div>
                    <div class="text-sm text-gray-400">High Demand Crops</div>
                </div>
            </div>
        </div>

        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-400">insights</span>
                </div>
                <div>
                    <div class="text-2xl font-bold text-white">${{ number_format($insights['best_opportunity']['difference'] ?? 0) }}</div>
                    <div class="text-sm text-gray-400">Avg. Export Premium</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Crop Market Cards -->
    <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
        <span class="material-symbols-outlined text-indigo-400">grain</span>
        Crop Market Prices
    </h2>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        @foreach($crops as $cropKey => $crop)
        <div class="bg-dark-800 border border-dark-700 rounded-2xl overflow-hidden hover:border-indigo-500/50 transition-colors">
            <!-- Card Header -->
            <div class="p-5 border-b border-dark-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="text-3xl">{{ $crop['icon'] }}</div>
                        <div>
                            <h3 class="text-lg font-semibold text-white">{{ $crop['name'] }}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="material-symbols-outlined text-sm {{ $crop['best_market'] === 'global' ? 'text-green-400' : 'text-amber-400' }}">
                                    {{ $crop['best_market'] === 'global' ? 'flight_takeoff' : 'home' }}
                                </span>
                                <span class="text-xs {{ $crop['best_market'] === 'global' ? 'text-green-400' : 'text-amber-400' }}">
                                    Best: {{ ucfirst($crop['best_market']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                    @if($crop['best_market'] === 'global')
                    <span class="px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-xs font-medium">Export Ready</span>
                    @endif
                </div>
            </div>

            <!-- Market Prices Grid -->
            <div class="grid grid-cols-3 divide-x divide-dark-700">
                <!-- Tanzania -->
                <div class="p-4 text-center">
                    <div class="text-xs text-gray-400 mb-2">🇹🇿 Tanzania</div>
                    <div class="text-lg font-bold text-white">${{ number_format($crop['markets']['tanzania']['price']) }}</div>
                    <div class="text-xs text-gray-500 mb-2">per ton</div>
                    <div class="flex items-center justify-center gap-1">
                        <span class="px-2 py-0.5 rounded-full bg-{{ $crop['markets']['tanzania']['demand_color'] }}-500/20 text-{{ $crop['markets']['tanzania']['demand_color'] }}-400 text-xs">
                            {{ $crop['markets']['tanzania']['demand'] }}
                        </span>
                    </div>
                    <div class="mt-2 text-xs {{ $crop['markets']['tanzania']['trend_up'] ? 'text-green-400' : 'text-red-400' }}">
                        {{ $crop['markets']['tanzania']['trend'] }}
                    </div>
                </div>

                <!-- Africa -->
                <div class="p-4 text-center">
                    <div class="text-xs text-gray-400 mb-2">🌍 Africa</div>
                    <div class="text-lg font-bold text-white">${{ number_format($crop['markets']['africa']['price']) }}</div>
                    <div class="text-xs text-gray-500 mb-2">per ton</div>
                    <div class="flex items-center justify-center gap-1">
                        <span class="px-2 py-0.5 rounded-full bg-{{ $crop['markets']['africa']['demand_color'] }}-500/20 text-{{ $crop['markets']['africa']['demand_color'] }}-400 text-xs">
                            {{ $crop['markets']['africa']['demand'] }}
                        </span>
                    </div>
                    <div class="mt-2 text-xs {{ $crop['markets']['africa']['trend_up'] ? 'text-green-400' : 'text-red-400' }}">
                        {{ $crop['markets']['africa']['trend'] }}
                    </div>
                </div>

                <!-- Global -->
                <div class="p-4 text-center bg-indigo-900/10">
                    <div class="text-xs text-indigo-300 mb-2">🌐 Global</div>
                    <div class="text-lg font-bold text-indigo-300">${{ number_format($crop['markets']['global']['price']) }}</div>
                    <div class="text-xs text-gray-500 mb-2">per ton</div>
                    <div class="flex items-center justify-center gap-1">
                        <span class="px-2 py-0.5 rounded-full bg-{{ $crop['markets']['global']['demand_color'] }}-500/20 text-{{ $crop['markets']['global']['demand_color'] }}-400 text-xs">
                            {{ $crop['markets']['global']['demand'] }}
                        </span>
                    </div>
                    <div class="mt-2 text-xs {{ $crop['markets']['global']['trend_up'] ? 'text-green-400' : 'text-red-400' }}">
                        {{ $crop['markets']['global']['trend'] }}
                    </div>
                </div>
            </div>

            <!-- Reason -->
            <div class="px-5 py-3 bg-dark-900/50 border-t border-dark-700">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-indigo-400 text-sm mt-0.5">info</span>
                    <p class="text-xs text-gray-400">{{ $crop['reason'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Legend -->
    <div class="mt-8 flex flex-wrap gap-4 justify-center">
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500"></span>
            <span class="text-sm text-gray-400">High Demand</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
            <span class="text-sm text-gray-400">Medium Demand</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500"></span>
            <span class="text-sm text-gray-400">Low Demand</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-green-400 text-sm">trending_up</span>
            <span class="text-sm text-gray-400">Price Increasing</span>
        </div>
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-red-400 text-sm">trending_down</span>
            <span class="text-sm text-gray-400">Price Decreasing</span>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.4s ease-out;
}
</style>
@endsection
