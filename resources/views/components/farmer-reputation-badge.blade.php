@props(['user', 'showDetails' => false])

@php
$trustScore = $user->blockchain_trust_score ?? 0;
$trustTier = $user->trust_tier ?? 'Average';
$isRegistered = $user->is_blockchain_registered ?? false;

$tierColors = [
    'Excellent' => 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20',
    'Good' => 'text-blue-400 bg-blue-500/10 border-blue-500/20',
    'Average' => 'text-yellow-400 bg-yellow-500/10 border-yellow-500/20',
    'Poor' => 'text-red-400 bg-red-500/10 border-red-500/20',
];

$tierColor = $tierColors[$trustTier] ?? $tierColors['Average'];
$scorePercentage = min(100, max(0, $trustScore / 10));
@endphp

<div class="space-y-3">
    {{-- Trust Score Badge --}}
    <div class="flex items-center gap-3">
        @if($isRegistered)
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full {{ $tierColor }} border">
                <span class="material-symbols-outlined text-sm">verified_user</span>
                <span class="text-sm font-medium">{{ $trustTier }}</span>
                <span class="text-xs opacity-75">({{ $trustScore }}/1000)</span>
            </div>
        @else
            <div class="flex items-center gap-2 px-3 py-1.5 rounded-full bg-slate-700/50 text-slate-400 border border-slate-600/30">
                <span class="material-symbols-outlined text-sm">person_off</span>
                <span class="text-sm font-medium">Not on Blockchain</span>
            </div>
        @endif
    </div>

    @if($isRegistered && $showDetails)
        {{-- Progress Bar --}}
        <div class="space-y-1">
            <div class="flex justify-between text-xs text-slate-400">
                <span>Trust Score</span>
                <span>{{ number_format($scorePercentage, 1) }}%</span>
            </div>
            <div class="h-2 bg-slate-700 rounded-full overflow-hidden">
                <div 
                    class="h-full rounded-full transition-all duration-500
                        {{ $trustScore >= 900 ? 'bg-emerald-500' : ($trustScore >= 750 ? 'bg-blue-500' : ($trustScore >= 500 ? 'bg-yellow-500' : 'bg-red-500')) }}"
                    style="width: {{ $scorePercentage }}%"
                ></div>
            </div>
        </div>

        {{-- Stats Grid --}}
        <div class="grid grid-cols-3 gap-2">
            <div class="bg-slate-800/50 rounded-lg p-2 text-center">
                <div class="text-lg font-bold text-emerald-400">
                    {{ $user->blockchain_successful_deliveries ?? 0 }}
                </div>
                <div class="text-xs text-slate-400">Successful</div>
            </div>
            <div class="bg-slate-800/50 rounded-lg p-2 text-center">
                <div class="text-lg font-bold text-slate-200">
                    {{ $user->blockchain_total_transactions ?? 0 }}
                </div>
                <div class="text-xs text-slate-400">Total Orders</div>
            </div>
            <div class="bg-slate-800/50 rounded-lg p-2 text-center">
                <div class="text-lg font-bold {{ ($user->blockchain_failed_deliveries ?? 0) > 0 ? 'text-red-400' : 'text-emerald-400' }}">
                    {{ $user->blockchain_failed_deliveries ?? 0 }}
                </div>
                <div class="text-xs text-slate-400">Failed</div>
            </div>
        </div>

        {{-- Wallet Address --}}
        @if($user->wallet_address)
            <div class="flex items-center gap-2 text-xs text-slate-500 bg-slate-800/30 rounded-lg p-2">
                <span class="material-symbols-outlined text-sm">wallet</span>
                <span class="font-mono truncate">{{ substr($user->wallet_address, 0, 10) }}...{{ substr($user->wallet_address, -8) }}</span>
                <a 
                    href="https://sepolia.etherscan.io/address/{{ $user->wallet_address }}" 
                    target="_blank"
                    class="ml-auto text-emerald-400 hover:text-emerald-300"
                >
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
            </div>
        @endif
    @endif
</div>
