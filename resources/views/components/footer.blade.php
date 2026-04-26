<footer class="bg-dark-800 border-t border-dark-700">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Brand -->
            <div class="md:col-span-1">
                <a href="{{ route('home') }}" class="inline-block mb-4">
                    <x-logo size="sm" :show-text="true" />
                </a>
                <p class="text-sm text-gray-500">
                    Modern agriculture platform connecting farmers, buyers, and suppliers across Africa.
                </p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-semibold text-white mb-4">Platform</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Marketplace</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Market Prices</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Logistics</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Expert Advice</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Company</h4>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-primary-400 transition-colors">About Us</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Careers</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Contact</a></li>
                    <li><a href="#" class="hover:text-primary-400 transition-colors">Blog</a></li>
                </ul>
            </div>

            <div>
                <h4 class="font-semibold text-white mb-4">Connect</h4>
                <div class="flex gap-3">
                    <a href="#" class="w-10 h-10 rounded-lg bg-dark-700 flex items-center justify-center text-gray-400 hover:bg-primary-600 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-sm">chat</span>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-dark-700 flex items-center justify-center text-gray-400 hover:bg-primary-600 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-sm">mail</span>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-lg bg-dark-700 flex items-center justify-center text-gray-400 hover:bg-primary-600 hover:text-white transition-all">
                        <span class="material-symbols-outlined text-sm">call</span>
                    </a>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-8 border-t border-dark-700 flex flex-col md:flex-row items-center justify-between gap-4">
            <p class="text-sm text-gray-500">
                &copy; {{ date('Y') }} AgroSphere. All rights reserved.
            </p>
            <div class="flex gap-6 text-sm text-gray-500">
                <a href="#" class="hover:text-gray-300 transition-colors">Privacy Policy</a>
                <a href="#" class="hover:text-gray-300 transition-colors">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
