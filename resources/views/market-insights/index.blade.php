@extends('layouts.dashboard')

@section('title', 'Market Insights')
@section('page-title', 'Global Market Insights')

@push('styles')
<style>
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .trend-line {
        fill: none;
        stroke-width: 4;
        stroke-linecap: round;
        stroke-linejoin: round;
        filter: drop-shadow(0 0 6px currentColor);
    }
    .trend-area {
        opacity: 0.2;
    }
    .chart-point {
        r: 5;
        transition: r 0.2s;
        filter: drop-shadow(0 0 4px currentColor);
    }
    .chart-point:hover {
        r: 7;
    }
    .market-card {
        transition: all 0.3s ease;
    }
    .market-card:hover {
        transform: translateY(-4px);
    }
    .score-ring {
        transform: rotate(-90deg);
        transform-origin: 50% 50%;
    }
    .score-circle {
        fill: none;
        stroke-width: 4;
    }
</style>
@endpush

@section('page-content')
<div class="space-y-6">
    @php
        $tzAvg = round(array_sum($priceTrends['tanzania']) / count($priceTrends['tanzania']));
        $afAvg = round(array_sum($priceTrends['africa']) / count($priceTrends['africa']));
        $glAvg = round(array_sum($priceTrends['global']) / count($priceTrends['global']));
    @endphp

    <!-- Header Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-sm">Tanzania Market</span>
                <span class="material-symbols-outlined text-primary-400">trending_up</span>
            </div>
            <div class="text-2xl font-bold text-white">${{ $tzAvg }}</div>
            <div class="text-xs text-primary-400">+12% vs last month</div>
        </div>
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-sm">Africa Average</span>
                <span class="material-symbols-outlined text-accent-orange">trending_flat</span>
            </div>
            <div class="text-2xl font-bold text-white">${{ $afAvg }}</div>
            <div class="text-xs text-gray-400">Stable</div>
        </div>
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-sm">Global Average</span>
                <span class="material-symbols-outlined text-accent-blue">trending_up</span>
            </div>
            <div class="text-2xl font-bold text-white">${{ $glAvg }}</div>
            <div class="text-xs text-accent-blue">+3% vs last month</div>
        </div>
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700 border-primary-600/50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-gray-500 text-sm">Best Market for {{ $selectedLocation }}</span>
                <span class="material-symbols-outlined text-green-400">emoji_events</span>
            </div>
            <div class="text-2xl font-bold text-white">{{ $bestMarket['flag'] }} {{ $bestMarket['country'] }}</div>
            <div class="text-xs text-green-400">Score: {{ $bestMarket['calculated_score'] }}/100 • {{ $farmerZone }} Zone</div>
        </div>
    </div>

    <!-- Price Trends Chart -->
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
            <div>
                <h3 class="text-lg font-semibold text-white">Price Trends</h3>
                <p class="text-sm text-gray-500">{{ $selectedCrop }} prices comparison across regions (USD/kg)</p>
            </div>
            <div class="flex items-center gap-4">
                <!-- Location Selector -->
                <form method="GET" class="flex items-center gap-2" id="location-form">
                    <span class="material-symbols-outlined text-gray-400">location_on</span>
                    <select name="location" onchange="document.getElementById('crop-form').location.value = this.value; this.form.submit()" 
                            class="px-3 py-2 rounded-lg bg-dark-900 border border-dark-700 text-white text-sm focus:border-primary-500 focus:outline-none cursor-pointer">
                        @foreach($farmerLocations as $location => $data)
                            <option value="{{ $location }}" {{ $selectedLocation === $location ? 'selected' : '' }}>
                                {{ $location }} ({{ $data['zone'] }})
                            </option>
                        @endforeach
                    </select>
                </form>
                
                <!-- Crop Selector -->
                <form method="GET" class="flex items-center gap-2" id="crop-form">
                    <input type="hidden" name="location" value="{{ $selectedLocation }}">
                    <span class="material-symbols-outlined text-gray-400">agriculture</span>
                    <select name="crop" onchange="this.form.submit()" 
                            class="px-3 py-2 rounded-lg bg-dark-900 border border-dark-700 text-white text-sm focus:border-primary-500 focus:outline-none cursor-pointer">
                        @foreach($crops as $crop)
                            <option value="{{ $crop['name'] }}" {{ $selectedCrop === $crop['name'] ? 'selected' : '' }}>
                                {{ $crop['name'] }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <div class="flex items-center gap-4 text-sm">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background: #00ff9d; box-shadow: 0 0 8px #00ff9d;"></span>
                        <span class="text-gray-400">Tanzania</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background: #ffb347; box-shadow: 0 0 8px #ffb347;"></span>
                        <span class="text-gray-400">Africa</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full" style="background: #00d4ff; box-shadow: 0 0 8px #00d4ff;"></span>
                        <span class="text-gray-400">Global</span>
                    </div>
                </div>
            </div>
        </div>

        @php
            // Calculate min and max for proper scaling
            $allPrices = array_merge($priceTrends['tanzania'], $priceTrends['africa'], $priceTrends['global']);
            $minPrice = min($allPrices) * 0.9; // 10% padding below
            $maxPrice = max($allPrices) * 1.1; // 10% padding above
            $priceRange = $maxPrice - $minPrice;
            
            // Helper function to calculate Y coordinate
            $getY = function($price) use ($minPrice, $priceRange) {
                return 260 - (($price - $minPrice) / $priceRange) * 220; // 260 bottom, 40 top
            };
            
            // Helper to build path
            $buildPath = function($prices) use ($getY) {
                $points = [];
                foreach ($prices as $index => $price) {
                    $x = ($index / 11) * 800;
                    $y = $getY($price);
                    $points[] = ($index === 0 ? 'M' : 'L') . $x . ',' . $y;
                }
                return implode(' ', $points);
            };
            
            // Helper to build area path
            $buildAreaPath = function($prices) use ($getY) {
                $points = [];
                foreach ($prices as $index => $price) {
                    $x = ($index / 11) * 800;
                    $y = $getY($price);
                    $points[] = ($index === 0 ? 'M' : 'L') . $x . ',' . $y;
                }
                // Close the area at bottom
                $points[] = 'L800,260 L0,260 Z';
                return implode(' ', $points);
            };
        @endphp

        <!-- SVG Line Chart -->
        <div class="chart-container">
            <svg viewBox="0 0 800 300" class="w-full h-full">
                <!-- Grid lines - 5 horizontal lines -->
                <g class="grid">
                    @for($i = 0; $i <= 4; $i++)
                        @php
                            $gridY = 260 - ($i * 55);
                            $gridValue = round($minPrice + ($priceRange * ($i / 4)));
                        @endphp
                        <line x1="50" y1="{{ $gridY }}" x2="800" y2="{{ $gridY }}" stroke="#1f2937" stroke-width="1" stroke-dasharray="4,4"/>
                        <text x="5" y="{{ $gridY + 4 }}" fill="#6b7280" font-size="11">${{ $gridValue }}</text>
                    @endfor
                </g>

                <!-- Tanzania Area & Line - Bright Emerald -->
                <path class="trend-area" d="{{ $buildAreaPath($priceTrends['tanzania']) }}" fill="url(#tzGradient)" opacity="0.3"/>
                <path class="trend-line" d="{{ $buildPath($priceTrends['tanzania']) }}" stroke="#00ff9d" stroke-width="4"/>
                
                <!-- Africa Area & Line - Vibrant Amber -->
                <path class="trend-area" d="{{ $buildAreaPath($priceTrends['africa']) }}" fill="url(#afGradient)" opacity="0.2"/>
                <path class="trend-line" d="{{ $buildPath($priceTrends['africa']) }}" stroke="#ffb347" stroke-width="4"/>
                
                <!-- Global Area & Line - Electric Cyan -->
                <path class="trend-area" d="{{ $buildAreaPath($priceTrends['global']) }}" fill="url(#glGradient)" opacity="0.2"/>
                <path class="trend-line" d="{{ $buildPath($priceTrends['global']) }}" stroke="#00d4ff" stroke-width="4"/>

                <!-- Data points for all lines -->
                @foreach($priceTrends['labels'] as $index => $label)
                    @php
                        $cx = ($index / 11) * 800;
                        $tzCy = $getY($priceTrends['tanzania'][$index]);
                        $afCy = $getY($priceTrends['africa'][$index]);
                        $glCy = $getY($priceTrends['global'][$index]);
                    @endphp
                    <circle class="chart-point" cx="{{ $cx }}" cy="{{ $tzCy }}" r="5" fill="#00ff9d"/>
                    <circle class="chart-point" cx="{{ $cx }}" cy="{{ $afCy }}" r="5" fill="#ffb347"/>
                    <circle class="chart-point" cx="{{ $cx }}" cy="{{ $glCy }}" r="5" fill="#00d4ff"/>
                @endforeach

                <!-- X Axis Labels -->
                @foreach($priceTrends['labels'] as $index => $label)
                    <text x="{{ ($index / 11) * 800 }}" y="290" fill="#6b7280" font-size="11" text-anchor="middle">{{ $label }}</text>
                @endforeach

                <!-- Gradients -->
                <defs>
                    <linearGradient id="tzGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#00ff9d;stop-opacity:0.4"/>
                        <stop offset="100%" style="stop-color:#00ff9d;stop-opacity:0"/>
                    </linearGradient>
                    <linearGradient id="afGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#ffb347;stop-opacity:0.3"/>
                        <stop offset="100%" style="stop-color:#ffb347;stop-opacity:0"/>
                    </linearGradient>
                    <linearGradient id="glGradient" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" style="stop-color:#00d4ff;stop-opacity:0.3"/>
                        <stop offset="100%" style="stop-color:#00d4ff;stop-opacity:0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
    </div>

    <!-- Recommended Markets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Best Market Card (Large) -->
        <div class="lg:col-span-2 p-6 rounded-2xl bg-gradient-to-br from-primary-900/30 to-dark-800 border border-primary-600/30">
            <div class="flex items-start justify-between mb-6">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <span class="material-symbols-outlined text-yellow-400">emoji_events</span>
                        <span class="text-sm text-primary-400 font-medium">Best Market to Sell</span>
                    </div>
                    <h3 class="text-2xl font-bold text-white">{{ $bestMarket['country'] }}</h3>
                    <p class="text-gray-400 text-sm">{{ $bestMarket['region'] }} Region</p>
                </div>
                <div class="text-right">
                    <div class="text-4xl">{{ $bestMarket['flag'] }}</div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4 mb-6">
                @foreach($bestMarket['crops'] as $crop)
                    <div class="p-4 rounded-xl bg-dark-900/50 border border-dark-700">
                        <div class="text-sm text-gray-400 mb-1">{{ $crop['name'] }}</div>
                        <div class="text-xl font-bold text-white">${{ $crop['price'] }}</div>
                        <div class="text-xs {{ $crop['trend_up'] ? 'text-green-400' : 'text-red-400' }}">
                            {{ $crop['trend_up'] ? '↑' : '↓' }} {{ $crop['trend'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between p-4 rounded-xl bg-primary-600/10 border border-primary-600/20">
                <div class="flex items-center gap-3">
                    <span class="material-symbols-outlined text-primary-400">analytics</span>
                    <div>
                        <div class="text-sm text-gray-400">Market Score</div>
                        <div class="text-lg font-semibold text-white">{{ $bestMarket['calculated_score'] }}/100</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-sm text-gray-400">Demand Level</div>
                    <div class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-green-500/20 text-green-400 text-sm">
                        <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
                        {{ $bestMarket['demand'] }}
                    </div>
                </div>
            </div>

            <!-- Map Placeholder -->
            <div class="mt-6 p-4 rounded-xl bg-dark-900/50 border border-dark-700 border-dashed">
                <div class="flex items-center justify-center gap-3 py-8 text-gray-400">
                    <span class="material-symbols-outlined text-3xl">map</span>
                    <div class="text-center">
                        <div class="text-sm font-medium">Interactive Map Coming Soon</div>
                        <div class="text-xs text-gray-500">Visualize market locations and transport routes</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommended Markets List -->
        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-primary-400">recommend</span>
                Recommended Markets
            </h3>
            
            <div class="space-y-4">
                @foreach($recommendedMarkets as $index => $market)
                    @php
                        $colors = ['primary', 'green', 'blue'];
                        $color = $colors[$index] ?? 'primary';
                    @endphp
                    <div class="market-card p-4 rounded-xl bg-dark-900 border border-dark-700 hover:border-{{ $color }}-600/50">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-3">
                                <span class="text-2xl">{{ $market['flag'] }}</span>
                                <div>
                                    <div class="font-medium text-white">{{ $market['country'] }}</div>
                                    <div class="text-xs text-gray-500">{{ $market['region'] }} Region • {{ $market['demand'] }} Demand</div>
                                </div>
                            </div>
                            <div class="w-12 h-12 relative">
                                <svg viewBox="0 0 36 36" class="w-full h-full score-ring">
                                    <circle cx="18" cy="18" r="14" stroke="#1f2937" class="score-circle"/>
                                    <circle cx="18" cy="18" r="14" stroke="currentColor" 
                                            class="score-circle text-{{ $color }}-500"
                                            stroke-dasharray="{{ $market['calculated_score'] }}, 100"
                                            stroke-linecap="round"/>
                                </svg>
                                <span class="absolute inset-0 flex items-center justify-center text-xs font-semibold text-white">{{ $market['calculated_score'] }}</span>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-{{ $color }}-400">{{ count($market['crops']) }} crops available</span>
                            <button class="px-3 py-1.5 rounded-lg bg-{{ $color }}-600/20 text-{{ $color }}-400 text-xs hover:bg-{{ $color }}-600/30 transition-colors">
                                View Details
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Market Comparison Table -->
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-white">Regional Market Comparison</h3>
            <div class="flex gap-2">
                <select class="px-3 py-2 rounded-lg bg-dark-900 border border-dark-700 text-sm text-gray-400">
                    <option>All Crops</option>
                    <option>Maize</option>
                    <option>Rice</option>
                    <option>Coffee</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="text-left text-gray-500 text-sm border-b border-dark-700">
                        <th class="pb-3 font-medium">Market</th>
                        <th class="pb-3 font-medium">Region</th>
                        <th class="pb-3 font-medium">Top Crop</th>
                        <th class="pb-3 font-medium">Price</th>
                        <th class="pb-3 font-medium">Trend</th>
                        <th class="pb-3 font-medium">Score</th>
                        <th class="pb-3 font-medium">Action</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach($markets as $market)
                        <tr class="border-b border-dark-700/50">
                            <td class="py-4">
                                <div class="flex items-center gap-3">
                                    <span class="text-2xl">{{ $market['flag'] }}</span>
                                    <span class="text-white font-medium">{{ $market['country'] }}</span>
                                </div>
                            </td>
                            <td class="py-4 text-gray-400">{{ $market['region'] }}</td>
                            <td class="py-4 text-white">{{ $market['crops'][0]['name'] }}</td>
                            <td class="py-4 text-white font-semibold">${{ $market['crops'][0]['price'] }}/{{ $market['crops'][0]['unit'] }}</td>
                            <td class="py-4">
                                <span class="{{ $market['crops'][0]['trend_up'] ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $market['crops'][0]['trend_up'] ? '↑' : '↓' }} {{ $market['crops'][0]['trend'] }}
                                </span>
                            </td>
                            <td class="py-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-2 rounded-full bg-dark-700">
                                        <div class="h-full rounded-full bg-primary-500" style="width: {{ $market['score'] }}%"></div>
                                    </div>
                                    <span class="text-white">{{ $market['score'] }}</span>
                                </div>
                            </td>
                            <td class="py-4">
                                <button class="px-3 py-1.5 rounded-lg bg-primary-600 text-white text-xs hover:bg-primary-500 transition-colors">
                                    Sell Here
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
