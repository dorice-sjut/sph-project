@props(['verified' => false, 'batchId' => null, 'txHash' => null, 'size' => 'md'])

@php
$sizes = [
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-sm px-2.5 py-1',
    'lg' => 'text-base px-3 py-1.5',
];

$sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

@if($verified)
    <span 
        class="inline-flex items-center gap-1.5 {{ $sizeClass }} rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium"
        title="Verified on blockchain - Batch: {{ $batchId }}"
    >
        <span class="material-symbols-outlined text-[1em]">verified</span>
        <span>Blockchain Verified</span>
        @if($txHash)
            <a 
                href="https://sepolia.etherscan.io/tx/{{ $txHash }}" 
                target="_blank" 
                rel="noopener noreferrer"
                class="hover:text-emerald-300 transition-colors"
                title="View on Etherscan"
            >
                <span class="material-symbols-outlined text-[1em]">open_in_new</span>
            </a>
        @endif
    </span>
    
    @if($batchId)
        <span class="block text-xs text-slate-400 mt-1 font-mono">
            Batch: {{ $batchId }}
        </span>
    @endif
@else
    <span 
        class="inline-flex items-center gap-1.5 {{ $sizeClass }} rounded-full bg-slate-700/50 text-slate-400 border border-slate-600/30 font-medium"
        title="Not verified on blockchain"
    >
        <span class="material-symbols-outlined text-[1em]">unverified</span>
        <span>Not Verified</span>
    </span>
@endif
