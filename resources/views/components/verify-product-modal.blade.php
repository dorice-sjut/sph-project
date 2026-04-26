@props(['product'])

<div 
    x-data="{ 
        open: false,
        batchId: '{{ $product->batch_id ?? '' }}',
        loading: false,
        generateBatchId() {
            const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            let result = 'AGRO-';
            for (let i = 0; i < 8; i++) {
                result += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            this.batchId = result;
        }
    }"
    x-init="if (!batchId) generateBatchId()"
>
    {{-- Trigger Button --}}
    @if(!$product->is_blockchain_verified)
        <button 
            @click="open = true"
            class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all shadow-lg shadow-emerald-900/20"
        >
            <span class="material-symbols-outlined">verified</span>
            <span>Verify on Blockchain</span>
        </button>
    @else
        <div class="flex items-center gap-2 text-emerald-400">
            <span class="material-symbols-outlined">check_circle</span>
            <span class="text-sm">Already Verified</span>
        </div>
    @endif

    {{-- Modal --}}
    <div 
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/70 backdrop-blur-sm z-50 flex items-center justify-center p-4"
        @click.self="open = false"
        style="display: none;"
    >
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            @click.stop
            class="bg-slate-900 rounded-2xl border border-slate-700 shadow-2xl max-w-md w-full p-6"
        >
            {{-- Header --}}
            <div class="flex items-center gap-3 mb-6">
                <div class="w-12 h-12 rounded-xl bg-emerald-500/10 flex items-center justify-center">
                    <span class="material-symbols-outlined text-emerald-400 text-2xl">verified</span>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-white">Blockchain Verification</h3>
                    <p class="text-sm text-slate-400">Verify product origin on-chain</p>
                </div>
            </div>

            {{-- Product Info --}}
            <div class="bg-slate-800/50 rounded-xl p-4 mb-6 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Product:</span>
                    <span class="text-white font-medium">{{ $product->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Farmer:</span>
                    <span class="text-white font-medium">{{ $product->user->name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-slate-400">Location:</span>
                    <span class="text-white font-medium">{{ $product->location }}</span>
                </div>
                @if($product->is_organic)
                    <div class="flex items-center gap-1 text-emerald-400 text-sm mt-2">
                        <span class="material-symbols-outlined text-sm">eco</span>
                        <span>Organic Certified</span>
                    </div>
                @endif
            </div>

            {{-- Batch ID Input --}}
            <div class="space-y-2 mb-6">
                <label class="text-sm font-medium text-slate-300">Batch ID</label>
                <div class="flex gap-2">
                    <input 
                        type="text" 
                        x-model="batchId"
                        class="flex-1 bg-slate-800 border border-slate-600 rounded-xl px-4 py-2.5 text-white text-sm focus:outline-none focus:border-emerald-500 font-mono"
                        placeholder="Enter batch ID"
                    >
                    <button 
                        @click="generateBatchId()"
                        type="button"
                        class="px-3 py-2 bg-slate-700 hover:bg-slate-600 text-slate-300 rounded-xl transition-colors"
                        title="Generate new batch ID"
                    >
                        <span class="material-symbols-outlined text-sm">refresh</span>
                    </button>
                </div>
                <p class="text-xs text-slate-500">
                    Unique identifier for this product batch on the blockchain
                </p>
            </div>

            {{-- Info Box --}
            <div class="bg-blue-500/10 border border-blue-500/20 rounded-xl p-3 mb-6">
                <div class="flex items-start gap-2">
                    <span class="material-symbols-outlined text-blue-400 text-sm mt-0.5">info</span>
                    <p class="text-xs text-blue-300">
                        This will create an immutable record on the blockchain verifying your product's origin, 
                        harvest date, and authenticity. This builds trust with buyers.
                    </p>
                </div>
            </div>

            {{-- Actions --}
            <form 
                method="POST" 
                action="{{ route('blockchain.verify-product', $product) }}"
                @submit="loading = true"
            >
                @csrf
                <input type="hidden" name="batch_id" x-model="batchId">
                
                <div class="flex gap-3">
                    <button 
                        type="button"
                        @click="open = false"
                        class="flex-1 px-4 py-2.5 bg-slate-700 hover:bg-slate-600 text-white rounded-xl transition-colors"
                    >
                        Cancel
                    </button>
                    <button 
                        type="submit"
                        :disabled="!batchId || loading"
                        class="flex-1 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 disabled:bg-slate-600 disabled:cursor-not-allowed text-white rounded-xl transition-all flex items-center justify-center gap-2"
                    >
                        <span x-show="loading" class="animate-spin">
                            <span class="material-symbols-outlined text-sm">progress_activity</span>
                        </span>
                        <span x-text="loading ? 'Verifying...' : 'Verify Now'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
