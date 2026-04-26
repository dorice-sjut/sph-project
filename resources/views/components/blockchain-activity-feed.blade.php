@props(['activities' => []])

<div class="bg-slate-800/50 rounded-xl border border-slate-700/50 overflow-hidden">
    <div class="px-5 py-4 border-b border-slate-700/50 flex items-center justify-between">
        <div class="flex items-center gap-2">
            <span class="material-symbols-outlined text-emerald-400">receipt_long</span>
            <h3 class="font-semibold text-white">Blockchain Activity</h3>
        </div>
        <span class="text-xs px-2 py-1 rounded-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
            Demo Mode
        </span>
    </div>
    
    <div class="max-h-96 overflow-y-auto">
        @forelse($activities as $activity)
            <div class="px-5 py-3 flex items-start gap-3 hover:bg-slate-700/20 transition-colors border-b border-slate-700/30 last:border-0">
                {{-- Icon --}}
                <div class="w-10 h-10 rounded-lg bg-{{ $activity['color'] }}-500/10 flex items-center justify-center flex-shrink-0">
                    <span class="material-symbols-outlined text-{{ $activity['color'] }}-400">
                        {{ $activity['icon'] }}
                    </span>
                </div>
                
                {{-- Content --}}
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between gap-2">
                        <p class="text-sm font-medium text-white">{{ $activity['title'] }}</p>
                        <span class="text-xs text-slate-500 whitespace-nowrap">
                            {{ $activity['timestamp']->diffForHumans() }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-400 mt-0.5">{{ $activity['description'] }}</p>
                    
                    {{-- TX Hash --}}
                    <div class="flex items-center gap-2 mt-2">
                        <code class="text-xs font-mono text-slate-500 bg-slate-900/50 px-2 py-0.5 rounded">
                            {{ substr($activity['tx_hash'], 0, 16) }}...
                        </code>
                        <span class="inline-flex items-center gap-1 text-xs px-1.5 py-0.5 rounded-full 
                            {{ $activity['status'] === 'confirmed' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-amber-500/10 text-amber-400' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $activity['status'] === 'confirmed' ? 'bg-emerald-400' : 'bg-amber-400' }}"></span>
                            {{ ucfirst($activity['status']) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <div class="px-5 py-8 text-center">
                <span class="material-symbols-outlined text-4xl text-slate-600 mb-2">inbox</span>
                <p class="text-slate-500 text-sm">No blockchain activity yet</p>
                <p class="text-slate-600 text-xs mt-1">Activities will appear when products are verified or orders are placed</p>
            </div>
        @endforelse
    </div>
    
    @if(count($activities) > 0)
        <div class="px-5 py-3 border-t border-slate-700/50 bg-slate-800/30">
            <div class="flex items-center justify-between text-xs">
                <span class="text-slate-500">Network: <span class="text-slate-300">Sepolia Testnet</span></span>
                <a href="https://sepolia.etherscan.io" target="_blank" class="text-emerald-400 hover:text-emerald-300 flex items-center gap-1">
                    View on Etherscan
                    <span class="material-symbols-outlined text-sm">open_in_new</span>
                </a>
            </div>
        </div>
    @endif
</div>
