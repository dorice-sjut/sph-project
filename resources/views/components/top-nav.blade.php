@php
$user = auth()->user();
$unreadMessages = $user ? $user->receivedMessages()->unread()->count() : 0;
$userRoles = $user ? $user->getRoleNames() : [];
$currentRole = $user ? ($user->primaryRole()?->name ?? $user->role) : null;
@endphp

<header class="sticky top-0 z-20 bg-slate-900/95 backdrop-blur-xl border-b border-slate-700 shadow-sm shadow-black/20">
    <div class="flex items-center justify-between h-16 px-4 lg:px-8">
        <!-- Left Side - Page Title -->
        <div class="flex items-center gap-4 lg:ml-0 ml-12">
            <!-- Sidebar Toggle Button (Desktop) -->
            <button @click="$dispatch('toggle-sidebar')"
                    class="hidden lg:flex p-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-400 hover:text-emerald-400 hover:border-emerald-600/30 transition-all"
                    title="Toggle Sidebar">
                <span class="material-symbols-outlined text-sm">menu_open</span>
            </button>
            <h2 class="text-lg font-semibold text-white">{{ $pageTitle ?? 'Dashboard' }}</h2>
        </div>

        <!-- Right Side -->
        <div class="flex items-center gap-4">
            <!-- Search -->
            <div class="hidden md:flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-800 border border-slate-700">
                <span class="material-symbols-outlined text-slate-500 text-sm">search</span>
                <input type="text"
                       placeholder="Search..."
                       class="bg-transparent border-none outline-none text-sm text-slate-300 placeholder-slate-500 w-48">
            </div>

            <!-- Role Switcher (shown if user has multiple roles) -->
            @if(count($userRoles) > 1)
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open"
                            class="flex items-center gap-2 px-3 py-2 rounded-xl bg-emerald-600/20 border border-emerald-600/30 text-emerald-400 hover:bg-emerald-600/30 transition-all">
                        <span class="material-symbols-outlined text-sm">switch_account</span>
                        <span class="text-sm font-medium capitalize">{{ $currentRole }}</span>
                        <span class="material-symbols-outlined text-sm">expand_more</span>
                    </button>

                    <div x-show="open"
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 rounded-xl bg-slate-800 border border-slate-700 shadow-xl shadow-black/30 py-2">
                        <div class="px-4 py-2 border-b border-slate-700">
                            <p class="text-xs text-slate-400">Switch Role</p>
                        </div>
                        @foreach($userRoles as $roleName)
                            @php $role = \App\Models\Role::where('name', $roleName)->first(); @endphp
                            <form method="POST" action="{{ route('switch-role') }}" class="block">
                                @csrf
                                <input type="hidden" name="role" value="{{ $roleName }}">
                                <button type="submit" 
                                        class="w-full flex items-center gap-3 px-4 py-2.5 text-sm {{ $currentRole === $roleName ? 'text-emerald-400 bg-emerald-600/10' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">
                                    <span class="material-symbols-outlined text-sm">{{ $role?->icon ?? 'person' }}</span>
                                    <span class="capitalize">{{ $role?->display_name ?? $roleName }}</span>
                                    @if($currentRole === $roleName)
                                        <span class="material-symbols-outlined text-sm ml-auto">check</span>
                                    @endif
                                </button>
                            </form>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Language Switcher -->
            <div x-data="{ open: false }" class="relative z-50">
                @php
                    $currentLang = \App\Http\Controllers\LanguageController::getCurrentLanguage();
                    $languages = \App\Http\Controllers\LanguageController::getAvailableLanguages();
                @endphp
                <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-300 hover:bg-slate-700 transition-all">
                    <span class="text-lg">{{ $currentLang['flag'] }}</span>
                    <span class="text-sm font-medium">{{ $currentLang['code'] }}</span>
                    <span class="material-symbols-outlined text-sm text-slate-400">expand_more</span>
                </button>

                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-48 rounded-xl bg-slate-800 border border-slate-700 shadow-xl shadow-black/30 py-2 z-50">
                    <div class="px-4 py-2 border-b border-slate-700">
                        <p class="text-xs text-slate-400">{{ __('messages.switch_language') }}</p>
                    </div>
                    @foreach($languages as $code => $lang)
                        <form method="POST" action="{{ route('switch.language') }}" class="block">
                            @csrf
                            <input type="hidden" name="language" value="{{ $code }}">
                            <button type="submit"
                                    class="w-full flex items-center gap-3 px-4 py-2.5 text-sm {{ $currentLang['code'] === $lang['code'] ? 'text-emerald-400 bg-emerald-600/10' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">
                                <span class="text-lg">{{ $lang['flag'] }}</span>
                                <span>{{ $lang['native'] }}</span>
                                @if($currentLang['code'] === $lang['code'])
                                    <span class="material-symbols-outlined text-sm ml-auto">check</span>
                                @endif
                            </button>
                        </form>
                    @endforeach
                </div>
            </div>

            <!-- Notifications -->
            <button class="relative p-2 rounded-xl bg-slate-800 border border-slate-700 text-slate-400 hover:text-white hover:bg-slate-700 transition-all shadow-sm">
                <span class="material-symbols-outlined">notifications</span>
                @if($unreadMessages > 0)
                    <span class="absolute top-1 right-1 w-2 h-2 bg-emerald-500 rounded-full"></span>
                @endif
            </button>

            <!-- User Menu -->
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open"
                        class="flex items-center gap-3 p-1.5 pr-4 rounded-xl bg-slate-800 border border-slate-700 hover:bg-slate-700 transition-all shadow-sm">
                    <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=10b981&color=fff' }}"
                         alt="{{ $user->name }}"
                         class="w-8 h-8 rounded-lg object-cover">
                    <div class="hidden md:block text-left">
                        <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400 capitalize">{{ $user->role }}</p>
                    </div>
                    <span class="material-symbols-outlined text-slate-500 text-sm">expand_more</span>
                </button>

                <!-- Dropdown -->
                <div x-show="open"
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 rounded-xl bg-slate-800 border border-slate-700 shadow-xl shadow-black/30 py-2">

                    <div class="px-4 py-3 border-b border-slate-700">
                        <p class="text-sm font-medium text-white">{{ $user->name }}</p>
                        <p class="text-xs text-slate-400">{{ $user->email }}</p>
                    </div>

                    <a href="{{ route('profile') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-400 hover:bg-slate-700 hover:text-white">
                        <span class="material-symbols-outlined text-sm">person</span>
                        Profile
                    </a>
                    <a href="{{ route('settings') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-slate-400 hover:bg-slate-700 hover:text-white">
                        <span class="material-symbols-outlined text-sm">settings</span>
                        Settings
                    </a>
                    <div class="border-t border-slate-700 my-2"></div>
                    <form method="POST" action="{{ route('logout') }}" class="px-4">
                        @csrf
                        <button type="submit" class="w-full flex items-center gap-3 py-2.5 text-sm text-red-400 hover:text-red-300">
                            <span class="material-symbols-outlined text-sm">logout</span>
                            Sign Out
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
