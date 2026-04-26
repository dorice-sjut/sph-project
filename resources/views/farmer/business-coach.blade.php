@extends('layouts.dashboard')

@section('page-title', 'AI Business Coach')

@section('content')
<div class="p-6 max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center gap-3 mb-2">
            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-blue-600 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-600/20">
                <span class="material-symbols-outlined text-white text-2xl">business_center</span>
            </div>
            <div>
                <h1 class="text-2xl font-bold text-white">AI Business Coach</h1>
                <p class="text-gray-400">Plan your farm business with AI-powered projections</p>
            </div>
        </div>
    </div>

    <!-- Input Form -->
    <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6 mb-8">
        <h2 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
            <span class="material-symbols-outlined text-blue-400">edit_note</span>
            Farm Details
        </h2>
        
        <form action="{{ route('farmer.business-coach.calculate') }}" method="POST" class="grid grid-cols-1 md:grid-cols-3 gap-4">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Crop Type</label>
                <select name="crop_type" required class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Select crop...</option>
                    @foreach($crops as $crop)
                        <option value="{{ $crop }}">{{ $crop }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Farm Size (acres)</label>
                <input type="number" name="farm_size" step="0.1" min="0.1" max="1000" required
                       placeholder="e.g., 5"
                       class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-blue-500 focus:outline-none">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-400 mb-2">Location</label>
                <select name="location" required class="w-full px-4 py-3 rounded-xl bg-dark-900 border border-dark-700 text-white focus:border-blue-500 focus:outline-none">
                    <option value="">Select location...</option>
                    @foreach($locations as $key => $name)
                        <option value="{{ $key }}">{{ ucfirst($name) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3">
                <button type="submit" class="w-full md:w-auto px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-blue-700 text-white font-medium hover:from-blue-500 hover:to-blue-600 transition-all shadow-lg shadow-blue-600/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined">calculate</span>
                    Generate Business Plan
                </button>
            </div>
        </form>
    </div>

    <!-- Results -->
    @if($calculation)
    <div class="space-y-6 animate-fade-in">
        <!-- Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Total Cost -->
            <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-red-500/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-red-400">payments</span>
                    </div>
                    <span class="text-gray-400 text-sm">Total Investment</span>
                </div>
                <div class="text-2xl font-bold text-white">
                    TZS {{ number_format($calculation['total_cost']) }}
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ $calculation['farm_size'] }} acres</div>
            </div>

            <!-- Expected Yield -->
            <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-amber-400">grass</span>
                    </div>
                    <span class="text-gray-400 text-sm">Expected Yield</span>
                </div>
                <div class="text-2xl font-bold text-white">
                    {{ $calculation['yield'] }} bags
                </div>
                <div class="text-xs text-gray-500 mt-1">{{ $calculation['crop_name'] }}</div>
            </div>

            <!-- Revenue -->
            <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl bg-purple-500/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-purple-400">attach_money</span>
                    </div>
                    <span class="text-gray-400 text-sm">Gross Revenue</span>
                </div>
                <div class="text-2xl font-bold text-white">
                    TZS {{ number_format($calculation['revenue']) }}
                </div>
                <div class="text-xs text-gray-500 mt-1">Estimated sales</div>
            </div>

            <!-- Profit -->
            <div class="bg-dark-800 border border-dark-700 rounded-2xl p-5">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-xl {{ $calculation['profit'] >= 0 ? 'bg-green-500/20' : 'bg-red-500/20' }} flex items-center justify-center">
                        <span class="material-symbols-outlined {{ $calculation['profit'] >= 0 ? 'text-green-400' : 'text-red-400' }}">trending_up</span>
                    </div>
                    <span class="text-gray-400 text-sm">Net Profit</span>
                </div>
                <div class="text-2xl font-bold {{ $calculation['profit'] >= 0 ? 'text-green-400' : 'text-red-400' }}">
                    TZS {{ number_format($calculation['profit']) }}
                </div>
                <div class="text-xs {{ $calculation['roi'] >= 0 ? 'text-green-500' : 'text-red-500' }} mt-1">{{ $calculation['roi'] }}% ROI</div>
            </div>
        </div>

        <!-- Cost Breakdown -->
        <div class="bg-dark-800 border border-dark-700 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400">pie_chart</span>
                Cost Breakdown
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-dark-900 rounded-xl p-4 border border-dark-700">
                    <div class="text-gray-400 text-sm mb-1">Seeds</div>
                    <div class="text-lg font-semibold text-white">TZS {{ number_format($calculation['costs']['seeds']) }}</div>
                </div>
                <div class="bg-dark-900 rounded-xl p-4 border border-dark-700">
                    <div class="text-gray-400 text-sm mb-1">Fertilizer</div>
                    <div class="text-lg font-semibold text-white">TZS {{ number_format($calculation['costs']['fertilizer']) }}</div>
                </div>
                <div class="bg-dark-900 rounded-xl p-4 border border-dark-700">
                    <div class="text-gray-400 text-sm mb-1">Pesticides</div>
                    <div class="text-lg font-semibold text-white">TZS {{ number_format($calculation['costs']['pesticides']) }}</div>
                </div>
                <div class="bg-dark-900 rounded-xl p-4 border border-dark-700">
                    <div class="text-gray-400 text-sm mb-1">Labor</div>
                    <div class="text-lg font-semibold text-white">TZS {{ number_format($calculation['costs']['labor']) }}</div>
                </div>
            </div>
        </div>

        <!-- AI Recommendations -->
        <div class="bg-gradient-to-r from-blue-900/30 to-purple-900/30 border border-blue-700/30 rounded-2xl p-6">
            <h3 class="text-lg font-semibold text-white mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-blue-400">lightbulb</span>
                AI Recommendations
            </h3>
            <div class="space-y-3">
                @if($calculation['profit'] > 0)
                    <div class="flex items-start gap-3 bg-dark-800/50 rounded-xl p-3">
                        <span class="material-symbols-outlined text-green-400">check_circle</span>
                        <div>
                            <div class="text-white font-medium">Profitable Venture</div>
                            <div class="text-gray-400 text-sm">Expected profit of TZS {{ number_format($calculation['profit']) }} makes this a viable investment.</div>
                        </div>
                    </div>
                @else
                    <div class="flex items-start gap-3 bg-dark-800/50 rounded-xl p-3">
                        <span class="material-symbols-outlined text-amber-400">warning</span>
                        <div>
                            <div class="text-white font-medium">Review Required</div>
                            <div class="text-gray-400 text-sm">Current projections show a loss. Consider reducing costs or finding better markets.</div>
                        </div>
                    </div>
                @endif

                <div class="flex items-start gap-3 bg-dark-800/50 rounded-xl p-3">
                    <span class="material-symbols-outlined text-blue-400">schedule</span>
                    <div>
                        <div class="text-white font-medium">Timeline</div>
                        <div class="text-gray-400 text-sm">Expected harvest in {{ $calculation['duration'] }} months. Plan cash flow accordingly.</div>
                    </div>
                </div>

                <div class="flex items-start gap-3 bg-dark-800/50 rounded-xl p-3">
                    <span class="material-symbols-outlined text-purple-400">savings</span>
                    <div>
                        <div class="text-white font-medium">Financial Planning</div>
                        <div class="text-gray-400 text-sm">Set aside 10-15% of total cost as emergency fund for unexpected expenses.</div>
                    </div>
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
