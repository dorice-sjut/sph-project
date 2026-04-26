@extends('layouts.landing')

@section('title', 'Home')

@section('page-content')
<!-- Hero Section -->
<section class="relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Video Background -->
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop playsinline class="w-full h-full object-cover opacity-40">
            <source src="https://assets.mixkit.co/videos/preview/mixkit-green-corn-field-waving-with-the-wind-5046-large.mp4" type="video/mp4">
        </video>
        <div class="absolute inset-0 bg-gradient-to-b from-dark-900/80 via-dark-900/60 to-dark-900"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-20">
        <div class="text-center max-w-4xl mx-auto">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-primary-900/30 border border-primary-700/50 mb-8 animate-fade-in">
                <span class="w-2 h-2 rounded-full bg-primary-400 animate-pulse"></span>
                <span class="text-sm text-primary-300">Transforming Agriculture in Africa</span>
            </div>

            <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight animate-slide-up">
                Cultivating <span class="text-gradient">Tomorrow's</span> Harvest Today
            </h1>

            <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto animate-slide-up" style="animation-delay: 0.1s;">
                Connect with buyers, suppliers, and experts. Access real-time market prices and grow your agricultural business with AgroSphere.
            </p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up" style="animation-delay: 0.2s;">
                <a href="{{ route('register') }}"
                   class="group px-8 py-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all flex items-center gap-3">
                    Get Started Free
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                </a>
                <a href="#features"
                   class="px-8 py-4 rounded-xl border border-dark-600 text-gray-300 hover:text-white hover:border-gray-500 font-semibold transition-all flex items-center gap-3">
                    <span class="material-symbols-outlined">play_circle</span>
                    Learn More
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mt-20 pt-10 border-t border-dark-700/50 animate-slide-up" style="animation-delay: 0.3s;">
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">10K+</div>
                    <div class="text-sm text-gray-500">Active Farmers</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">50K+</div>
                    <div class="text-sm text-gray-500">Products Listed</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">$2M+</div>
                    <div class="text-sm text-gray-500">Transactions</div>
                </div>
                <div class="text-center">
                    <div class="text-3xl md:text-4xl font-bold text-white mb-1">15+</div>
                    <div class="text-sm text-gray-500">Countries</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 -translate-x-1/2 z-10 animate-bounce">
        <span class="material-symbols-outlined text-gray-500 text-2xl">keyboard_arrow_down</span>
    </div>
</section>

<!-- Features Section -->
<section id="features" class="py-24 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1 rounded-full bg-primary-900/30 border border-primary-700/50 text-primary-400 text-sm font-medium mb-4">
                Platform Features
            </span>
            <h2 class="text-4xl font-bold text-white mb-4">Everything You Need to Thrive</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                From farm to market, we provide the tools and connections you need to succeed in modern agriculture.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="group p-6 rounded-2xl bg-dark-800 border border-dark-700 hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="w-14 h-14 rounded-xl bg-primary-600/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary-400 text-2xl">storefront</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Digital Marketplace</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Sell your produce directly to buyers. List products with images, set your prices, and manage orders in one place.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="group p-6 rounded-2xl bg-dark-800 border border-dark-700 hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="w-14 h-14 rounded-xl bg-accent-blue/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-accent-blue text-2xl">trending_up</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Market Insights</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Real-time price data from Tanzania, Africa, and global markets. Make informed decisions with AI-powered recommendations.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="group p-6 rounded-2xl bg-dark-800 border border-dark-700 hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="w-14 h-14 rounded-xl bg-accent-orange/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-accent-orange text-2xl">local_shipping</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Logistics Network</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Connect with reliable transport partners. Track deliveries in real-time and optimize your supply chain.
                </p>
            </div>

            <!-- Feature 4 -->
            <div class="group p-6 rounded-2xl bg-dark-800 border border-dark-700 hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="w-14 h-14 rounded-xl bg-accent-purple/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-accent-purple text-2xl">chat</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Direct Messaging</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Chat with buyers, suppliers, and agricultural experts. Negotiate deals and get advice instantly.
                </p>
            </div>

            <!-- Feature 5 -->
            <div class="group p-6 rounded-2xl bg-dark-800 border border-dark-700 hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="w-14 h-14 rounded-xl bg-primary-600/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-primary-400 text-2xl">location_on</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Smart Matching</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Location-based recommendations find the best markets and buyers near you. Reduce transport costs and increase profits.
                </p>
            </div>

            <!-- Feature 6 -->
            <div class="group p-6 rounded-2xl bg-dark-800 border border-dark-700 hover:border-primary-600/50 transition-all card-shadow hover:card-shadow-hover">
                <div class="w-14 h-14 rounded-xl bg-accent-blue/20 flex items-center justify-center mb-5 group-hover:scale-110 transition-transform">
                    <span class="material-symbols-outlined text-accent-blue text-2xl">verified</span>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Trusted Network</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Verified users, secure payments, and rating systems ensure safe and reliable transactions every time.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- How It Works Section -->
<section id="about" class="py-24 bg-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1 rounded-full bg-primary-900/30 border border-primary-700/50 text-primary-400 text-sm font-medium mb-4">
                Simple Process
            </span>
            <h2 class="text-4xl font-bold text-white mb-4">How It Works</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Get started in three easy steps and transform your agricultural journey.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="relative text-center">
                <div class="absolute top-16 left-full w-full h-0.5 bg-gradient-to-r from-primary-600/50 to-transparent hidden md:block" style="width: calc(100% - 4rem);"></div>
                <div class="w-20 h-20 mx-auto rounded-2xl bg-primary-600/20 border border-primary-600/30 flex items-center justify-center mb-6 relative z-10">
                    <span class="material-symbols-outlined text-primary-400 text-3xl">person_add</span>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-primary-600 text-white text-sm font-bold flex items-center justify-center">1</div>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Create Account</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Sign up as a farmer, buyer, supplier, or expert. Complete your profile and verify your identity.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="relative text-center">
                <div class="absolute top-16 left-full w-full h-0.5 bg-gradient-to-r from-primary-600/50 to-transparent hidden md:block" style="width: calc(100% - 4rem);"></div>
                <div class="w-20 h-20 mx-auto rounded-2xl bg-accent-blue/20 border border-accent-blue/30 flex items-center justify-center mb-6 relative z-10">
                    <span class="material-symbols-outlined text-accent-blue text-3xl">search</span>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-accent-blue text-white text-sm font-bold flex items-center justify-center">2</div>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Connect & Discover</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Browse the marketplace, find buyers or sellers, check market prices, and connect with the right partners.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="relative text-center">
                <div class="w-20 h-20 mx-auto rounded-2xl bg-accent-orange/20 border border-accent-orange/30 flex items-center justify-center mb-6 relative z-10">
                    <span class="material-symbols-outlined text-accent-orange text-3xl">swap_horiz</span>
                    <div class="absolute -top-2 -right-2 w-8 h-8 rounded-full bg-accent-orange text-white text-sm font-bold flex items-center justify-center">3</div>
                </div>
                <h3 class="text-xl font-semibold text-white mb-3">Trade & Grow</h3>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Place orders, negotiate deals, arrange logistics, and grow your business with real-time insights.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Roles Section -->
<section class="py-24 bg-dark-900">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1 rounded-full bg-primary-900/30 border border-primary-700/50 text-primary-400 text-sm font-medium mb-4">
                For Everyone
            </span>
            <h2 class="text-4xl font-bold text-white mb-4">Tailored For Your Role</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Whether you're growing, buying, supplying, or advising - AgroSphere has tools designed for you.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach([
                ['icon' => 'agriculture', 'title' => 'Farmers', 'desc' => 'List products, track orders, and get market insights to maximize your profits.', 'color' => 'primary'],
                ['icon' => 'shopping_cart', 'title' => 'Buyers', 'desc' => 'Discover fresh produce, compare prices, and connect directly with farmers.', 'color' => 'blue'],
                ['icon' => 'inventory', 'title' => 'Suppliers', 'desc' => 'Manage inventory and fulfill orders efficiently with our logistics tools.', 'color' => 'orange'],
                ['icon' => 'school', 'title' => 'Experts', 'desc' => 'Share knowledge, consult with farmers, and build your reputation.', 'color' => 'purple'],
                ['icon' => 'local_shipping', 'title' => 'Logistics', 'desc' => 'Optimize routes, manage deliveries, and grow your transport business.', 'color' => 'green'],
                ['icon' => 'admin_panel_settings', 'title' => 'Admins', 'desc' => 'Monitor platform health, manage users, and oversee market operations.', 'color' => 'primary'],
            ] as $role)
                <div class="group relative p-6 rounded-2xl bg-dark-900 border border-dark-700 hover:border-{{ $role['color'] }}-600/50 transition-all overflow-hidden">
                    <div class="absolute inset-0 bg-gradient-to-br from-{{ $role['color'] }}-600/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-12 h-12 rounded-xl bg-{{ $role['color'] }}-600/20 flex items-center justify-center mb-4">
                            <span class="material-symbols-outlined text-{{ $role['color'] }}-400 text-xl">{{ $role['icon'] }}</span>
                        </div>
                        <h3 class="text-lg font-semibold text-white mb-2">{{ $role['title'] }}</h3>
                        <p class="text-gray-400 text-sm">{{ $role['desc'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="py-24 bg-dark-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16">
            <span class="inline-block px-4 py-1 rounded-full bg-primary-900/30 border border-primary-700/50 text-primary-400 text-sm font-medium mb-4">
                Success Stories
            </span>
            <h2 class="text-4xl font-bold text-white mb-4">What Our Users Say</h2>
            <p class="text-gray-400 max-w-2xl mx-auto">
                Hear from farmers, buyers, and suppliers who have transformed their businesses with AgroSphere.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Testimonial 1 -->
            <div class="p-6 rounded-2xl bg-dark-900 border border-dark-700">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                    @endfor
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">
                    "AgroSphere helped me sell my maize harvest directly to buyers in Dar es Salaam. I got better prices and eliminated middlemen. My income increased by 40%!"
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-primary-600/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-primary-400">person</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">John Mwangi</h4>
                        <p class="text-gray-500 text-xs">Maize Farmer, Arusha</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 2 -->
            <div class="p-6 rounded-2xl bg-dark-900 border border-dark-700">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                    @endfor
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">
                    "As a restaurant owner, I need fresh produce daily. AgroSphere connects me directly with local farmers. The quality is amazing and delivery is always on time."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-accent-blue/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-accent-blue">person</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">Sarah Kimani</h4>
                        <p class="text-gray-500 text-xs">Restaurant Owner, Nairobi</p>
                    </div>
                </div>
            </div>

            <!-- Testimonial 3 -->
            <div class="p-6 rounded-2xl bg-dark-900 border border-dark-700">
                <div class="flex items-center gap-1 mb-4">
                    @for($i = 0; $i < 5; $i++)
                        <span class="material-symbols-outlined text-yellow-400 text-sm">star</span>
                    @endfor
                </div>
                <p class="text-gray-300 text-sm leading-relaxed mb-6">
                    "The market price insights feature is a game-changer. I now know when to sell and at what price. No more guessing or being cheated by brokers."
                </p>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 rounded-full bg-accent-orange/20 flex items-center justify-center">
                        <span class="material-symbols-outlined text-accent-orange">person</span>
                    </div>
                    <div>
                        <h4 class="font-semibold text-white text-sm">Grace Omondi</h4>
                        <p class="text-gray-500 text-xs">Vegetable Farmer, Kampala</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-24 bg-dark-900 relative overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[800px] h-[800px] bg-primary-600/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative z-10 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
            Ready to Transform Your <span class="text-gradient">Agricultural Business</span>?
        </h2>
        <p class="text-xl text-gray-400 mb-10 max-w-2xl mx-auto">
            Join thousands of farmers, buyers, and suppliers already using AgroSphere to grow their business.
        </p>
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ route('register') }}"
               class="px-8 py-4 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition-all">
                Create Free Account
            </a>
            <a href="{{ route('login') }}"
               class="px-8 py-4 rounded-xl border border-dark-600 text-gray-300 hover:text-white hover:border-gray-500 font-semibold transition-all">
                Sign In
            </a>
        </div>
    </div>
</section>
@endsection
