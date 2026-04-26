@extends('layouts.dashboard')

@section('page-title', 'Blockchain Dashboard')

@section('page-content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-white">Blockchain Dashboard</h1>
            <p class="text-slate-400 mt-1">Monitor on-chain activity and verified products</p>
        </div>
        <div class="flex items-center gap-2 px-4 py-2 bg-slate-800 rounded-xl border border-slate-700">
            <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-sm text-slate-300">{{ config('blockchain.network', 'sepolia') }}</span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Verified Products --}}
        <div class="bg-slate-800/50 rounded-xl p-5 border border-slate-700/50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-slate-400 text-sm">Verified Products</span>
                <span class="w-10 h-10 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-400">verified</span>
                </span>
            </div>
            <div class="text-3xl font-bold text-white">{{ $stats['verified_products'] ?? 0 }}</div>
            <div class="text-xs text-emerald-400 mt-1">On-chain verified</div>
        </div>

        {{-- Registered Farmers --}}
        <div class="bg-slate-800/50 rounded-xl p-5 border border-slate-700/50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-slate-400 text-sm">Registered Farmers</span>
                <span class="w-10 h-10 rounded-lg bg-blue-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-blue-400">person_check</span>
                </span>
            </div>
            <div class="text-3xl font-bold text-white">{{ $stats['registered_farmers'] ?? 0 }}</div>
            <div class="text-xs text-blue-400 mt-1">On blockchain</div>
        </div>

        {{-- Escrow Orders --}}
        <div class="bg-slate-800/50 rounded-xl p-5 border border-slate-700/50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-slate-400 text-sm">Escrow Orders</span>
                <span class="w-10 h-10 rounded-lg bg-purple-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-purple-400">lock</span>
                </span>
            </div>
            <div class="text-3xl font-bold text-white">{{ $stats['escrow_orders'] ?? 0 }}</div>
            <div class="text-xs text-purple-400 mt-1">Secure payments</div>
        </div>

        {{-- Total Transactions --}}
        <div class="bg-slate-800/50 rounded-xl p-5 border border-slate-700/50">
            <div class="flex items-center justify-between mb-3">
                <span class="text-slate-400 text-sm">Total Transactions</span>
                <span class="w-10 h-10 rounded-lg bg-amber-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-amber-400">receipt_long</span>
                </span>
            </div>
            <div class="text-3xl font-bold text-white">{{ $stats['total_transactions'] ?? 0 }}</div>
            <div class="text-xs text-amber-400 mt-1">{{ $stats['confirmed_transactions'] ?? 0 }} confirmed</div>
        </div>
    </div>

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent Transactions --}}
        <div class="bg-slate-800/50 rounded-xl border border-slate-700/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-700/50 flex items-center justify-between">
                <h3 class="font-semibold text-white">Recent Transactions</h3>
                <a href="{{ route('admin.blockchain.transactions') }}" class="text-sm text-emerald-400 hover:text-emerald-300">View All</a>
            </div>
            <div class="divide-y divide-slate-700/50">
                @forelse($recentTransactions as $tx)
                    <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-700/20 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg 
                                {{ $tx->status === 'confirmed' ? 'bg-emerald-500/10' : ($tx->status === 'pending' ? 'bg-amber-500/10' : 'bg-red-500/10') }} 
                                flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm
                                    {{ $tx->status === 'confirmed' ? 'text-emerald-400' : ($tx->status === 'pending' ? 'text-amber-400' : 'text-red-400') }}">
                                    {{ $tx->status === 'confirmed' ? 'check_circle' : ($tx->status === 'pending' ? 'pending' : 'error') }}
                                </span>
                            </div>
                            <div>
                                <p class="text-sm text-white">{{ $tx->function_name }}</p>
                                <p class="text-xs text-slate-500">{{ $tx->contract_name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400">{{ $tx->created_at->diffForHumans() }}</p>
                            @if($tx->tx_hash)
                                <a href="{{ $tx->etherscan_url }}" target="_blank" class="text-xs text-emerald-400 hover:text-emerald-300">
                                    {{ substr($tx->tx_hash, 0, 10) }}...
                                </a>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2">inbox</span>
                        <p>No transactions yet</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Verified Products --}}
        <div class="bg-slate-800/50 rounded-xl border border-slate-700/50 overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-700/50 flex items-center justify-between">
                <h3 class="font-semibold text-white">Recently Verified Products</h3>
                <a href="{{ route('admin.blockchain.products') }}" class="text-sm text-emerald-400 hover:text-emerald-300">View All</a>
            </div>
            <div class="divide-y divide-slate-700/50">
                @forelse($verifiedProducts as $product)
                    <div class="px-5 py-3 flex items-center justify-between hover:bg-slate-700/20 transition-colors">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm text-emerald-400">verified</span>
                            </div>
                            <div>
                                <p class="text-sm text-white">{{ $product->name }}</p>
                                <p class="text-xs text-slate-500">{{ $product->user->name }} • {{ $product->location }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-mono text-emerald-400">{{ $product->batch_id }}</p>
                            <p class="text-xs text-slate-500">{{ $product->blockchain_verified_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-8 text-center text-slate-500">
                        <span class="material-symbols-outlined text-4xl mb-2">inventory_2</span>
                        <p>No verified products yet</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Registered Farmers --}}
    <div class="bg-slate-800/50 rounded-xl border border-slate-700/50 overflow-hidden">
        <div class="px-5 py-4 border-b border-slate-700/50 flex items-center justify-between">
            <h3 class="font-semibold text-white">Registered Farmers</h3>
            <a href="{{ route('admin.blockchain.farmers') }}" class="text-sm text-emerald-400 hover:text-emerald-300">View All</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-700/30">
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-400">Farmer</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-400">Wallet</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-400">Trust Score</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-400">Transactions</th>
                        <th class="px-5 py-3 text-left text-xs font-medium text-slate-400">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700/50">
                    @forelse($registeredFarmers as $farmer)
                        <tr class="hover:bg-slate-700/20">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <span class="font-medium text-white">{{ $farmer->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <code class="text-xs text-slate-400">{{ substr($farmer->wallet_address, 0, 12) }}...</code>
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2">
                                    <div class="w-16 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full 
                                            {{ $farmer->blockchain_trust_score >= 900 ? 'bg-emerald-500' : ($farmer->blockchain_trust_score >= 750 ? 'bg-blue-500' : ($farmer->blockchain_trust_score >= 500 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                                            style="width: {{ min(100, $farmer->blockchain_trust_score / 10) }}%"></div>
                                    </div>
                                    <span class="text-xs text-white">{{ $farmer->blockchain_trust_score }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-3 text-sm text-slate-300">
                                {{ $farmer->blockchain_total_transactions }}
                                <span class="text-emerald-400">({{ $farmer->blockchain_successful_deliveries }} success)</span>
                            </td>
                            <td class="px-5 py-3">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs
                                    {{ $farmer->trust_tier === 'Excellent' ? 'bg-emerald-500/10 text-emerald-400' : ($farmer->trust_tier === 'Good' ? 'bg-blue-500/10 text-blue-400' : 'bg-yellow-500/10 text-yellow-400') }}">
                                    {{ $farmer->trust_tier }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-slate-500">
                                <span class="material-symbols-outlined text-4xl mb-2">group</span>
                                <p>No registered farmers yet</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Smart Contract Info --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
        $contracts = [
            ['name' => 'AgroSphereOrigin', 'purpose' => 'Product Verification', 'color' => 'emerald'],
            ['name' => 'AgroSphereEscrow', 'purpose' => 'Secure Payments', 'color' => 'purple'],
            ['name' => 'AgroSphereReputation', 'purpose' => 'Farmer Reputation', 'color' => 'blue'],
        ];
        @endphp

        @foreach($contracts as $contract)
            <div class="bg-slate-800/50 rounded-xl p-5 border border-slate-700/50">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-lg bg-{{ $contract['color'] }}-500/10 flex items-center justify-center">
                        <span class="material-symbols-outlined text-{{ $contract['color'] }}-400">contract</span>
                    </div>
                    <div>
                        <h4 class="font-medium text-white">{{ $contract['name'] }}</h4>
                        <p class="text-xs text-slate-400">{{ $contract['purpose'] }}</p>
                    </div>
                </div>
                <code class="block text-xs text-slate-500 bg-slate-900/50 rounded-lg p-2 break-all">
                    {{ config("blockchain.contracts." . strtolower(str_replace('AgroSphere', '', $contract['name'])) . ".address", 'Not deployed yet') }}
                </code>
            </div>
        @endforeach
    </div>
</div>
@endsection
