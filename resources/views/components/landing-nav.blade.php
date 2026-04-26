<nav x-data="{ scrolled: false, mobileOpen: false }"
     @scroll.window="scrolled = window.pageYOffset > 20"
     :class="scrolled ? 'bg-dark-900/95 backdrop-blur-xl border-b border-dark-700' : 'bg-transparent'"
     class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
            <!-- Logo -->
            <a href="{{ route('home') }}">
                <x-logo size="md" :show-text="true" />
            </a>

            <!-- Desktop Navigation -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm text-gray-300 hover:text-white transition-colors">Features</a>
                <a href="{{ route('buyer.marketplace') }}" class="text-sm text-gray-300 hover:text-white transition-colors">Marketplace</a>
                <a href="#testimonials" class="text-sm text-gray-300 hover:text-white transition-colors">Testimonials</a>
                <a href="#about" class="text-sm text-gray-300 hover:text-white transition-colors">How It Works</a>
            </div>

            <!-- CTA Buttons -->
            <div class="hidden md:flex items-center gap-4">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                       class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-all">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="text-sm text-gray-300 hover:text-white transition-colors">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="px-5 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium transition-all">
                        Get Started
                    </a>
                @endauth
            </div>

            <!-- Mobile Menu Button -->
            <button @click="mobileOpen = !mobileOpen"
                    class="md:hidden p-2 rounded-xl bg-dark-800 border border-dark-700 text-gray-300">
                <span class="material-symbols-outlined" x-text="mobileOpen ? 'close' : 'menu'">menu</span>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div x-show="mobileOpen"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="md:hidden bg-dark-800 border-b border-dark-700">
        <div class="px-4 py-6 space-y-4">
            <a href="#features" class="block text-gray-300 hover:text-white py-2">Features</a>
            <a href="{{ route('buyer.marketplace') }}" class="block text-gray-300 hover:text-white py-2">Marketplace</a>
            <a href="#testimonials" class="block text-gray-300 hover:text-white py-2">Testimonials</a>
            <a href="#about" class="block text-gray-300 hover:text-white py-2">How It Works</a>
            <div class="pt-4 border-t border-dark-700 flex flex-col gap-3">
                @auth
                    <a href="{{ route(auth()->user()->role . '.dashboard') }}"
                       class="block w-full text-center px-5 py-3 rounded-xl bg-primary-600 text-white font-medium">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('login') }}"
                       class="block text-center px-5 py-3 rounded-xl border border-dark-600 text-gray-300">
                        Sign In
                    </a>
                    <a href="{{ route('register') }}"
                       class="block text-center px-5 py-3 rounded-xl bg-primary-600 text-white font-medium">
                        Get Started
                    </a>
                @endauth
            </div>
        </div>
    </div>
</nav>
