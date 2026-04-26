@extends('layouts.dashboard')

@section('page-title', 'AI Budget Planner')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">AI Budget Planner</h1>
            <p class="text-gray-400">Smart farming budget recommendations powered by AI</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 rounded-xl bg-primary-600/20 border border-primary-600/30">
            <span class="material-symbols-outlined text-primary-400">smart_toy</span>
            <span class="text-sm text-primary-400 font-medium">AI Powered</span>
        </div>
    </div>

    <!-- Input Form -->
    <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-400">tune</span>
            Plan Your Farm Budget
        </h2>
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm text-gray-400 mb-2">Select Crop</label>
                <select name="crop" class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
                    @foreach($crops as $cropName)
                        <option value="{{ $cropName }}" {{ $selectedCrop === $cropName ? 'selected' : '' }}>
                            {{ $cropName }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Number of Acres</label>
                <input type="number" name="acres" value="{{ $acres }}" min="1" max="1000"
                       class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
            </div>
            <div>
                <label class="block text-sm text-gray-400 mb-2">Your Available Budget (USD)</label>
                <input type="number" name="budget" value="{{ $budget }}" min="0" placeholder="Enter your budget"
                       class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-primary-500 focus:outline-none">
            </div>
            <div class="md:col-span-3">
                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-primary-600 to-primary-700 text-white font-medium hover:from-primary-500 hover:to-primary-600 transition-all shadow-lg shadow-primary-600/20">
                    <span class="material-symbols-outlined inline-block mr-2">calculate</span>
                    Calculate Budget
                </button>
            </div>
        </form>
    </div>

    @if($acres > 0)
    <!-- Budget Breakdown -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Cost Breakdown -->
        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-accent-orange">payments</span>
                Cost Breakdown
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-600/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-400">grass</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Seeds</div>
                            <div class="text-xs text-gray-500">${{ $crop['seed_cost_per_acre'] }}/acre</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-white">${{ number_format($totalSeedCost) }}</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-accent-orange/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-accent-orange">science</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Fertilizer</div>
                            <div class="text-xs text-gray-500">${{ $crop['fertilizer_cost_per_acre'] }}/acre</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-white">${{ number_format($totalFertilizerCost) }}</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-accent-blue">group</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Labor</div>
                            <div class="text-xs text-gray-500">${{ $crop['labor_cost_per_acre'] }}/acre</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-white">${{ number_format($totalLaborCost) }}</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-600/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-purple-400">bug_report</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Pesticides</div>
                            <div class="text-xs text-gray-500">${{ $crop['pesticide_cost_per_acre'] }}/acre</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-white">${{ number_format($totalPesticideCost) }}</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-primary-600/10 border border-primary-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-600/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-400">account_balance_wallet</span>
                        </div>
                        <div class="text-white font-semibold">Total Investment</div>
                    </div>
                    <div class="text-2xl font-bold text-primary-400">${{ number_format($totalInvestment) }}</div>
                </div>
            </div>
        </div>

        <!-- Projected Returns -->
        <div class="p-6 rounded-2xl bg-dark-800 border border-dark-700">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-green-400">trending_up</span>
                Projected Returns
            </h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-600/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-400">agriculture</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Expected Yield</div>
                            <div class="text-xs text-gray-500">{{ $crop['expected_yield_per_acre'] }} bags/acre</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-white">{{ number_format($totalYield) }} bags</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-accent-blue/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-accent-blue">attach_money</span>
                        </div>
                        <div>
                            <div class="text-sm text-gray-400">Market Price</div>
                            <div class="text-xs text-gray-500">${{ $crop['market_price_per_unit'] }}/bag</div>
                        </div>
                    </div>
                    <div class="text-lg font-semibold text-white">${{ $crop['market_price_per_unit'] }}/bag</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-dark-900">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-600/20 flex items-center justify-center">
                            <span class="material-symbols-outlined text-primary-400">payments</span>
                        </div>
                        <div class="text-sm text-gray-400">Growing Period</div>
                    </div>
                    <div class="text-lg font-semibold text-white">{{ $crop['growing_months'] }} months</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl bg-green-600/10 border border-green-600/30">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-green-600/30 flex items-center justify-center">
                            <span class="material-symbols-outlined text-green-400">savings</span>
                        </div>
                        <div class="text-white font-semibold">Expected Revenue</div>
                    </div>
                    <div class="text-2xl font-bold text-green-400">${{ number_format($expectedRevenue) }}</div>
                </div>
                <div class="flex items-center justify-between p-4 rounded-xl {{ $netProfit >= 0 ? 'bg-green-600/10 border-green-600/30' : 'bg-red-600/10 border-red-600/30' }}">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl {{ $netProfit >= 0 ? 'bg-green-600/30' : 'bg-red-600/30' }} flex items-center justify-center">
                            <span class="material-symbols-outlined {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">{{ $netProfit >= 0 ? 'trending_up' : 'trending_down' }}</span>
                        </div>
                        <div class="text-white font-semibold">Net Profit</div>
                    </div>
                    <div class="text-2xl font-bold {{ $netProfit >= 0 ? 'text-green-400' : 'text-red-400' }}">{{ $netProfit >= 0 ? '+' : '' }}${{ number_format($netProfit) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- ROI & Risk Analysis -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-primary-400">percent</span>
                <span class="text-gray-400 text-sm">Return on Investment</span>
            </div>
            <div class="text-3xl font-bold {{ $roi >= 30 ? 'text-green-400' : ($roi >= 15 ? 'text-yellow-400' : 'text-red-400') }}">
                {{ $roi }}%
            </div>
            <div class="text-xs text-gray-500 mt-1">Per growing cycle</div>
        </div>
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-accent-orange">schedule</span>
                <span class="text-gray-400 text-sm">Payback Period</span>
            </div>
            <div class="text-3xl font-bold text-white">{{ $crop['growing_months'] }} months</div>
            <div class="text-xs text-gray-500 mt-1">Time to harvest</div>
        </div>
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <div class="flex items-center gap-2 mb-2">
                <span class="material-symbols-outlined text-accent-blue">shield</span>
                <span class="text-gray-400 text-sm">Risk Level</span>
            </div>
            <div class="text-3xl font-bold {{ $riskLevel === 'Low' ? 'text-green-400' : ($riskLevel === 'Medium' ? 'text-yellow-400' : 'text-red-400') }}">
                {{ $riskLevel }}
            </div>
            <div class="text-xs text-gray-500 mt-1">Based on ROI & duration</div>
        </div>
    </div>

    <!-- AI Recommendations -->
    <div class="p-6 rounded-2xl bg-gradient-to-br from-primary-900/30 to-dark-800 border border-primary-600/30">
        <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary-400">lightbulb</span>
            AI Recommendations
        </h3>
        <div class="space-y-3">
            @foreach($recommendations as $rec)
                <div class="flex items-start gap-4 p-4 rounded-xl {{ 
                    $rec['type'] === 'success' ? 'bg-green-600/10 border border-green-600/30' : 
                    ($rec['type'] === 'warning' ? 'bg-yellow-600/10 border border-yellow-600/30' : 
                    ($rec['type'] === 'error' ? 'bg-red-600/10 border border-red-600/30' : 'bg-primary-600/10 border border-primary-600/30')) 
                }}">
                    <div class="w-10 h-10 rounded-xl {{ 
                        $rec['type'] === 'success' ? 'bg-green-600/30' : 
                        ($rec['type'] === 'warning' ? 'bg-yellow-600/30' : 
                        ($rec['type'] === 'error' ? 'bg-red-600/30' : 'bg-primary-600/30')) 
                    }} flex items-center justify-center flex-shrink-0">
                        <span class="material-symbols-outlined {{ 
                            $rec['type'] === 'success' ? 'text-green-400' : 
                            ($rec['type'] === 'warning' ? 'text-yellow-400' : 
                            ($rec['type'] === 'error' ? 'text-red-400' : 'text-primary-400')) 
                        }}">{{ $rec['icon'] }}</span>
                    </div>
                    <div>
                        <div class="font-semibold text-white mb-1">{{ $rec['title'] }}</div>
                        <div class="text-sm text-gray-400">{{ $rec['message'] }}</div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Tips Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <h4 class="font-semibold text-white mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-green-400">savings</span>
                Cost Saving Tips
            </h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-green-400 text-base">check_circle</span>
                    Buy seeds in bulk for 10-15% discount
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-green-400 text-base">check_circle</span>
                    Use organic compost to reduce fertilizer costs
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-green-400 text-base">check_circle</span>
                    Join farmer cooperatives for shared equipment
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-green-400 text-base">check_circle</span>
                    Practice crop rotation to improve soil health
                </li>
            </ul>
        </div>
        <div class="p-5 rounded-2xl bg-dark-800 border border-dark-700">
            <h4 class="font-semibold text-white mb-3 flex items-center gap-2">
                <span class="material-symbols-outlined text-accent-orange">trending_up</span>
                Revenue Boosters
            </h4>
            <ul class="space-y-2 text-sm text-gray-400">
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-accent-orange text-base">star</span>
                    Target premium organic markets (+30% price)
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-accent-orange text-base">star</span>
                    Process crops before selling (value addition)
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-accent-orange text-base">star</span>
                    Sell directly to buyers via AgroSphere
                </li>
                <li class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-accent-orange text-base">star</span>
                    Time sales with peak demand periods
                </li>
            </ul>
        </div>
    </div>
</div>
@endsection
