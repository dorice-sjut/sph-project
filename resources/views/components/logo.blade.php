@props(['size' => 'md', 'showText' => true, 'variant' => 'default'])

@php
$sizes = [
    'sm' => ['icon' => 'w-8 h-8', 'text' => 'text-sm'],
    'md' => ['icon' => 'w-10 h-10', 'text' => 'text-lg'],
    'lg' => ['icon' => 'w-12 h-12', 'text' => 'text-xl'],
    'xl' => ['icon' => 'w-16 h-16', 'text' => 'text-2xl'],
];

$iconSize = $sizes[$size]['icon'] ?? $sizes['md']['icon'];
$textSize = $sizes[$size]['text'] ?? $sizes['md']['text'];

$bgClass = $variant === 'light' 
    ? 'bg-gradient-to-br from-emerald-400 via-green-500 to-teal-600' 
    : 'bg-gradient-to-br from-emerald-500 via-green-600 to-teal-700';
@endphp

<div class="flex items-center gap-3">
    {{-- Logo Icon with Animation --}}
    <div class="{{ $iconSize }} rounded-2xl {{ $bgClass }} flex items-center justify-center shadow-lg shadow-emerald-500/30 flex-shrink-0 relative overflow-hidden group">
        {{-- Animated background effect --}}
        <div class="absolute inset-0 bg-gradient-to-tr from-transparent via-white/20 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
        
        {{-- SVG Logo --}}
        <svg class="w-2/3 h-2/3 text-white relative z-10" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Globe base --}}
            <circle cx="16" cy="16" r="14" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.3"/>
            
            {{-- Globe lines --}}
            <ellipse cx="16" cy="16" rx="6" ry="14" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.3"/>
            <ellipse cx="16" cy="16" rx="14" ry="6" stroke="currentColor" stroke-width="1.5" stroke-opacity="0.3"/>
            
            {{-- Plant/Leaf --}}
            <path d="M16 24C16 24 16 20 16 16" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
            <path d="M16 16C16 16 12 14 12 10C12 6 16 4 16 4C16 4 20 6 20 10C20 14 16 16 16 16Z" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
            
            {{-- Left leaf --}}
            <path d="M16 18C16 18 10 16 8 12C6 8 10 6 10 6C10 6 14 8 16 12" fill="currentColor" fill-opacity="0.3" stroke="currentColor" stroke-width="1.5"/>
            
            {{-- Right leaf --}}
            <path d="M16 18C16 18 22 16 24 12C26 8 22 6 22 6C22 6 18 8 16 12" fill="currentColor" fill-opacity="0.3" stroke="currentColor" stroke-width="1.5"/>
            
            {{-- Dots for seeds/tech --}}
            <circle cx="10" cy="22" r="1.5" fill="currentColor"/>
            <circle cx="22" cy="22" r="1.5" fill="currentColor"/>
            <circle cx="16" cy="26" r="1.5" fill="currentColor"/>
        </svg>
    </div>
    
    @if($showText)
        {{-- Logo Text --}}
        <div class="whitespace-nowrap overflow-hidden">
            <h1 class="font-bold {{ $textSize }} text-white tracking-tight">
                <span class="bg-gradient-to-r from-emerald-400 via-green-400 to-teal-400 bg-clip-text text-transparent">Agro</span><span class="text-white">Sphere</span>
            </h1>
            @if(isset($role))
                <p class="text-xs text-emerald-400/80 capitalize font-medium tracking-wide">{{ $role }}</p>
            @endif
        </div>
    @endif
</div>
