@php
$role = auth()->user()->role ?? 'guest';

$navItems = [
    'farmer' => [
        ['icon' => 'dashboard', 'label' => __('messages.dashboard'), 'route' => 'farmer.dashboard'],
        ['icon' => 'inventory_2', 'label' => __('messages.my_products'), 'route' => 'farmer.products'],
        ['icon' => 'receipt_long', 'label' => __('messages.orders'), 'route' => 'farmer.orders'],
        ['icon' => 'analytics', 'label' => __('messages.market_insights'), 'route' => 'market.insights'],
        ['icon' => 'public', 'label' => __('messages.global_market'), 'route' => 'global.market'],
        ['icon' => 'smart_toy', 'label' => __('messages.ai_budget_planner'), 'route' => 'farmer.budget'],
        ['icon' => 'psychology', 'label' => __('messages.agro_ai'), 'route' => 'farmer.ai-assistant'],
        ['icon' => 'business_center', 'label' => __('messages.ai_business_coach'), 'route' => 'farmer.business-coach'],
        ['icon' => 'local_shipping', 'label' => __('messages.smart_selling'), 'route' => 'farmer.selling-advisor'],
        ['icon' => 'trending_up', 'label' => __('messages.market_prices'), 'route' => 'market.prices'],
        ['icon' => 'chat', 'label' => __('messages.messages'), 'route' => 'messages'],
        ['icon' => 'person', 'label' => __('messages.profile'), 'route' => 'profile'],
    ],
    'buyer' => [
        ['icon' => 'dashboard', 'label' => __('messages.dashboard'), 'route' => 'buyer.dashboard'],
        ['icon' => 'shopping_basket', 'label' => 'Marketplace', 'route' => 'buyer.marketplace'],
        ['icon' => 'receipt_long', 'label' => 'My Orders', 'route' => 'buyer.orders'],
        ['icon' => 'analytics', 'label' => __('messages.market_insights'), 'route' => 'market.insights'],
        ['icon' => 'chat', 'label' => __('messages.messages'), 'route' => 'messages'],
        ['icon' => 'person', 'label' => __('messages.profile'), 'route' => 'profile'],
    ],
    'supplier' => [
        ['icon' => 'dashboard', 'label' => __('messages.dashboard'), 'route' => 'supplier.dashboard'],
        ['icon' => 'local_shipping', 'label' => 'Supply Orders', 'route' => 'supplier.orders'],
        ['icon' => 'inventory', 'label' => 'Inventory', 'route' => 'supplier.inventory'],
        ['icon' => 'trending_up', 'label' => __('messages.market_prices'), 'route' => 'market.prices'],
        ['icon' => 'chat', 'label' => __('messages.messages'), 'route' => 'messages'],
        ['icon' => 'person', 'label' => __('messages.profile'), 'route' => 'profile'],
    ],
    'expert' => [
        ['icon' => 'dashboard', 'label' => __('messages.dashboard'), 'route' => 'expert.dashboard'],
        ['icon' => 'agriculture', 'label' => 'Consultations', 'route' => 'expert.consultations'],
        ['icon' => 'article', 'label' => 'Knowledge Base', 'route' => 'expert.knowledge'],
        ['icon' => 'chat', 'label' => __('messages.messages'), 'route' => 'messages'],
        ['icon' => 'person', 'label' => __('messages.profile'), 'route' => 'profile'],
    ],
    'logistics' => [
        ['icon' => 'dashboard', 'label' => __('messages.dashboard'), 'route' => 'logistics.dashboard'],
        ['icon' => 'local_shipping', 'label' => __('messages.deliveries'), 'route' => 'logistics.deliveries'],
        ['icon' => 'map', 'label' => 'Routes', 'route' => 'logistics.routes'],
        ['icon' => 'chat', 'label' => __('messages.messages'), 'route' => 'messages'],
        ['icon' => 'person', 'label' => __('messages.profile'), 'route' => 'profile'],
    ],
    'admin' => [
        ['icon' => 'dashboard', 'label' => __('messages.dashboard'), 'route' => 'admin.dashboard'],
        ['icon' => 'people', 'label' => 'Users', 'route' => 'admin.users'],
        ['icon' => 'inventory_2', 'label' => __('messages.products'), 'route' => 'admin.products'],
        ['icon' => 'receipt_long', 'label' => __('messages.orders'), 'route' => 'admin.orders'],
        ['icon' => 'trending_up', 'label' => 'Market Data', 'route' => 'admin.market'],
        ['icon' => 'link', 'label' => 'Blockchain', 'route' => 'admin.blockchain.dashboard'],
        ['icon' => 'settings', 'label' => __('messages.settings'), 'route' => 'admin.settings'],
    ],
];

$currentNav = $navItems[$role] ?? [];
@endphp

<!-- Mobile Menu Button -->
<div x-data="{ mobileOpen: false }">
    <button @click="mobileOpen = !mobileOpen"
            class="fixed top-4 left-4 z-50 p-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 hover:text-white hover:bg-slate-700 transition-colors shadow-lg shadow-black/30 lg:hidden">
        <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'"></span>
    </button>

    <!-- Mobile Overlay -->
    <div x-show="mobileOpen"
         x-transition.opacity.duration.300ms
         @click="mobileOpen = false"
         class="fixed inset-0 bg-black/50 z-30 lg:hidden">
    </div>
</div>

<!-- Sidebar -->
<aside x-data="{ 
        mobileOpen: false,
        collapsed: localStorage.getItem('sidebarCollapsed') === 'true'
       }"
       x-init="$watch('collapsed', value => localStorage.setItem('sidebarCollapsed', value))"
       :class="[
        mobileOpen ? 'translate-x-0' : '-translate-x-full',
        collapsed ? 'lg:w-20' : 'lg:w-64',
        'lg:translate-x-0'
       ]"
       @keydown.escape.window="mobileOpen = false"
       @toggle-sidebar.window="collapsed = !collapsed"
       class="fixed inset-y-0 left-0 z-40 bg-slate-900/95 backdrop-blur-xl transform transition-all duration-300 flex flex-col h-screen shadow-2xl shadow-black/40">

        <!-- Logo -->
        <div class="flex items-center gap-3 px-4 lg:px-6 py-5 flex-shrink-0 justify-between">
            <div class="flex items-center gap-3" :class="collapsed ? 'lg:hidden' : ''">
                <x-logo size="md" :role="$role" />
            </div>
            <!-- Collapse Toggle (Desktop only) -->
            <button @click="collapsed = !collapsed" 
                    class="hidden lg:flex p-2 rounded-xl bg-slate-800/50 text-slate-400 hover:text-emerald-400 hover:bg-slate-800 transition-all"
                    :class="collapsed ? 'mx-auto' : ''">
                <span class="material-symbols-outlined text-lg" x-text="collapsed ? 'chevron_right' : 'chevron_left'"></span>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-3 py-4 space-y-0.5 overflow-y-auto scrollbar-hide">
            @foreach($currentNav as $item)
                <a href="{{ route($item['route']) }}"
                   @click="mobileOpen = false"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                   {{ request()->routeIs($item['route']) || request()->routeIs($item['route'] . '.*')
                      ? 'bg-slate-800/80 text-emerald-400 shadow-sm'
                      : 'text-slate-400 hover:text-emerald-400 hover:bg-slate-800/40' }}"
                   :class="collapsed ? 'lg:justify-center' : ''">
                    <span class="material-symbols-outlined {{ request()->routeIs($item['route']) ? 'filled' : '' }} text-lg transition-colors flex-shrink-0">
                        {{ $item['icon'] }}
                    </span>
                    <span class="truncate" :class="collapsed ? 'lg:hidden' : ''">{{ $item['label'] }}</span>
                    @if(request()->routeIs($item['route']))
                        <span class="ml-auto w-1.5 h-1.5 rounded-full bg-emerald-400 flex-shrink-0 shadow-[0_0_8px_rgba(52,211,153,0.6)]" :class="collapsed ? 'lg:hidden' : ''"></span>
                    @endif
                </a>
            @endforeach
        </nav>

        <!-- Bottom Actions -->
        <div class="p-3 flex-shrink-0">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="flex items-center gap-3 px-4 py-2.5 rounded-xl text-sm font-medium text-slate-400 hover:text-red-400 hover:bg-red-500/10 transition-all"
                        :class="collapsed ? 'lg:justify-center lg:px-2' : 'w-full'">
                    <span class="material-symbols-outlined text-lg flex-shrink-0">logout</span>
                    <span :class="collapsed ? 'lg:hidden' : ''">{{ __('messages.logout') }}</span>
                </button>
            </form>
        </div>
    </aside>
