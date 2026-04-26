@extends('layouts.dashboard')

@section('page-title', 'Smart Selling Advisor')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-700 flex items-center justify-center shadow-lg shadow-emerald-600/20">
                <span class="material-symbols-outlined text-white text-2xl">local_shipping</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">Smart Selling Advisor</h1>
                <p class="text-gray-400">Find the best market to sell your crops for maximum profit</p>
            </div>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 mb-8">
        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-400">edit_note</span>
            Your Crop Details
        </h2>
        
        <form action="{{ route('farmer.selling-advisor.analyze') }}" method="POST" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Crop to Sell</label>
                <select name="crop" required class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-emerald-500 focus:outline-none">
                    <option value="">Select your crop...</option>
                    @foreach($crops as $crop)
                        <option value="{{ $crop['key'] }}">{{ $crop['icon'] }} {{ $crop['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Your Current Location</label>
                <select name="location" required class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-emerald-500 focus:outline-none">
                    <option value="">Select your location...</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc['key'] }}">{{ $loc['name'] }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-2">
                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-700 text-white font-medium hover:from-emerald-500 hover:to-teal-600 transition-all shadow-lg shadow-emerald-600/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">analytics</span>
                    Find Best Market
                </button>
            </div>
        </form>
    </div>

    <!-- Recommendation Results -->
    @if($recommendation)
    <div class="space-y-6 animate-fade-in">
        <!-- Best Market Card (Highlighted) -->
        <div class="bg-gradient-to-r from-emerald-900/40 via-teal-900/40 to-cyan-900/40 border border-emerald-500/50 rounded-2xl p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-emerald-500/20 to-teal-500/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="relative z-10">
                <div class="flex items-center gap-2 mb-4">
                    <span class="material-symbols-outlined text-emerald-400 text-2xl">stars</span>
                    <h2 class="text-xl font-bold text-white">Recommended Market</h2>
                    <span class="px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-400 text-xs font-medium">#1 Choice</span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Crop Info -->
                    <div class="flex items-center gap-4">
                        <div class="text-4xl">{{ $recommendation['crop_icon'] }}</div>
                        <div>
                            <div class="text-lg font-semibold text-white">{{ $recommendation['crop_name'] }}</div>
                            <div class="text-sm text-gray-400">{{ $recommendation['unit'] }}</div>
                        </div>
                    </div>

                    <!-- Best Location -->
                    <div class="bg-dark-900/50 rounded-xl p-4">
                        <div class="text-sm text-gray-400 mb-1">Best Market</div>
                        <div class="text-xl font-bold text-emerald-400">{{ $recommendation['best_market']['name'] }}</div>
                        <div class="text-xs text-gray-500">{{ $recommendation['best_market']['distance'] }} km from you</div>
                    </div>

                    <!-- Price -->
                    <div class="bg-dark-900/50 rounded-xl p-4">
                        <div class="text-sm text-gray-400 mb-1">Expected Price</div>
                        <div class="text-xl font-bold text-white">TZS {{ number_format($recommendation['best_market']['price']) }}</div>
                        <div class="text-xs text-emerald-400">{{ $recommendation['gain_percent'] }}% more than local</div>
                    </div>

                    <!-- Demand -->
                    <div class="bg-dark-900/50 rounded-xl p-4">
                        <div class="text-sm text-gray-400 mb-1">Demand Level</div>
                        <div class="flex items-center gap-2">
                            <span class="px-3 py-1 rounded-full 
                                {{ $recommendation['best_market']['demand'] === 'High' ? 'bg-green-500/20 text-green-400' : 
                                   ($recommendation['best_market']['demand'] === 'Medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                {{ $recommendation['best_market']['demand'] }}
                            </span>
                        </div>
                        <div class="text-xs text-gray-500">{{ $recommendation['best_market']['buyers'] }} active buyers</div>
                    </div>
                </div>

                <!-- Financial Analysis -->
                <div class="mt-6 pt-6 border-t border-emerald-500/20">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-dark-900/30 rounded-xl p-4">
                            <div class="text-sm text-gray-400 mb-1">Potential Gain</div>
                            <div class="text-lg font-bold text-emerald-400">+TZS {{ number_format($recommendation['potential_gain']) }}</div>
                            <div class="text-xs text-gray-500">per {{ $recommendation['unit'] }}</div>
                        </div>
                        <div class="bg-dark-900/30 rounded-xl p-4">
                            <div class="text-sm text-gray-400 mb-1">Est. Transport Cost</div>
                            <div class="text-lg font-bold text-amber-400">TZS {{ number_format($recommendation['transport_cost']) }}</div>
                            <div class="text-xs text-gray-500">{{ $recommendation['best_market']['distance'] }} km × 500 TZS/km</div>
                        </div>
                        <div class="bg-gradient-to-r from-emerald-600/20 to-teal-600/20 rounded-xl p-4 border border-emerald-500/30">
                            <div class="text-sm text-gray-400 mb-1">Net Benefit</div>
                            <div class="text-lg font-bold {{ $recommendation['net_benefit'] > 0 ? 'text-emerald-400' : 'text-red-400' }}">
                                {{ $recommendation['net_benefit'] > 0 ? '+' : '' }}TZS {{ number_format($recommendation['net_benefit']) }}
                            </div>
                            <div class="text-xs text-gray-500">after transport</div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="mt-6 flex flex-wrap gap-3">
                    <button class="px-6 py-2 rounded-xl bg-emerald-600 text-white hover:bg-emerald-500 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">map</span>
                        View on Map
                    </button>
                    <button class="px-6 py-2 rounded-xl bg-dark-700 text-white hover:bg-dark-600 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">contacts</span>
                        Find Buyers
                    </button>
                    <button class="px-6 py-2 rounded-xl bg-dark-700 text-white hover:bg-dark-600 transition-colors flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">share</span>
                        Share Analysis
                    </button>
                </div>
            </div>
        </div>

        <!-- All Markets Comparison -->
        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-emerald-400">leaderboard</span>
                All Markets Comparison
            </h3>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-dark-700">
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Rank</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Location</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Price</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Demand</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Buyers</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Distance</th>
                            <th class="text-left py-3 px-4 text-sm font-medium text-gray-400">Score</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recommendation['all_markets'] as $index => $market)
                        <tr class="border-b border-dark-700/50 {{ $market['is_farmer_location'] ? 'bg-emerald-900/10' : '' }} {{ $index === 0 ? 'bg-emerald-900/20' : '' }}">
                            <td class="py-3 px-4">
                                @if($index === 0)
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-emerald-500 text-white text-xs font-bold">1</span>
                                @elseif($index === 1)
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-slate-400 text-white text-xs font-bold">2</span>
                                @elseif($index === 2)
                                    <span class="flex items-center justify-center w-6 h-6 rounded-full bg-amber-600 text-white text-xs font-bold">3</span>
                                @else
                                    <span class="text-gray-500 text-sm">{{ $index + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <span class="text-white font-medium">{{ $market['name'] }}</span>
                                    @if($market['is_farmer_location'])
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs">You are here</span>
                                    @endif
                                    @if($index === 0)
                                        <span class="px-2 py-0.5 rounded-full bg-emerald-500/20 text-emerald-400 text-xs">Best</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 px-4">
                                <span class="text-white font-medium">TZS {{ number_format($market['price']) }}</span>
                            </td>
                            <td class="py-3 px-4">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $market['demand'] === 'High' ? 'bg-green-500/20 text-green-400' : 
                                       ($market['demand'] === 'Medium' ? 'bg-yellow-500/20 text-yellow-400' : 'bg-red-500/20 text-red-400') }}">
                                    {{ $market['demand'] }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-gray-400">{{ $market['buyers'] }}</td>
                            <td class="py-3 px-4 text-gray-400">{{ $market['distance'] }} km</td>
                            <td class="py-3 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 bg-dark-700 rounded-full overflow-hidden">
                                        <div class="h-full {{ $index === 0 ? 'bg-emerald-500' : 'bg-blue-500' }} rounded-full" style="width: {{ $market['score'] }}%"></div>
                                    </div>
                                    <span class="text-sm {{ $index === 0 ? 'text-emerald-400 font-medium' : 'text-gray-400' }}">{{ $market['score'] }}</span>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-gradient-to-r from-amber-900/20 to-orange-900/20 border border-amber-700/30 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-amber-400">lightbulb</span>
                Smart Selling Tips
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-400 mt-0.5">schedule</span>
                    <p class="text-sm text-gray-300">Sell during off-peak harvest seasons when local supply is low and prices are higher.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-400 mt-0.5">groups</span>
                    <p class="text-sm text-gray-300">Join farmer cooperatives to access bulk buyers and negotiate better prices.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-400 mt-0.5">verified</span>
                    <p class="text-sm text-gray-300">Get certified for quality standards to access premium markets and export opportunities.</p>
                </div>
                <div class="flex items-start gap-3">
                    <span class="material-symbols-outlined text-amber-400 mt-0.5">storage</span>
                    <p class="text-sm text-gray-300">Consider storage facilities to hold crops and sell when market prices peak.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
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
