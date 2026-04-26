<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MarketplaceController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\MarketPriceController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\MarketInsightsController;
use App\Http\Controllers\BudgetController;
use App\Http\Controllers\AIAssistantController;
use App\Http\Controllers\AIBusinessCoachController;
use App\Http\Controllers\GlobalMarketController;
use App\Http\Controllers\SmartSellingAdvisorController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\BlockchainController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [LandingController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'store']);
});

Route::post('/logout', [LoginController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

/*
|--------------------------------------------------------------------------
| Protected Routes - All Roles
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Role Switching
    Route::post('/switch-role', [DashboardController::class, 'switchRole'])->name('switch-role');

    // Language Switching
    Route::post('/switch-language', [LanguageController::class, 'switch'])->name('switch.language');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::get('/settings', [ProfileController::class, 'settings'])->name('settings');

    // Messages
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/messages/{user}', [MessageController::class, 'conversation'])->name('messages.conversation');
    Route::post('/messages/{user}', [MessageController::class, 'store']);

    // Market Prices
    Route::get('/market-prices', [MarketPriceController::class, 'index'])->name('market.prices');
    Route::get('/market-prices/{product}', [MarketPriceController::class, 'show']);

    /*
    |--------------------------------------------------------------------------
    | Farmer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:farmer')->prefix('farmer')->name('farmer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'farmer'])->name('dashboard');

        // Products
        Route::get('/products', [ProductController::class, 'index'])->name('products');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store']);
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update']);
        Route::delete('/products/{product}', [ProductController::class, 'destroy']);

        // Orders
        Route::get('/orders', [OrderController::class, 'farmerOrders'])->name('orders');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('/orders/{order}/status', [OrderController::class, 'updateStatus']);

        // AI Budget Planner
        Route::get('/budget', [BudgetController::class, 'index'])->name('budget');

        // AI Assistant
        Route::get('/ai-assistant', [AIAssistantController::class, 'index'])->name('ai-assistant');
        Route::post('/ai-assistant/chat', [AIAssistantController::class, 'chat'])->name('ai.chat');
        Route::delete('/ai-assistant/clear', [AIAssistantController::class, 'clearHistory'])->name('ai.clear');

        // AI Business Coach
        Route::get('/business-coach', [AIBusinessCoachController::class, 'index'])->name('business-coach');
        Route::post('/business-coach/calculate', [AIBusinessCoachController::class, 'calculate'])->name('business-coach.calculate');

        // Smart Selling Advisor
        Route::get('/selling-advisor', [SmartSellingAdvisorController::class, 'index'])->name('selling-advisor');
        Route::post('/selling-advisor/analyze', [SmartSellingAdvisorController::class, 'analyze'])->name('selling-advisor.analyze');
    });

    /*
    |--------------------------------------------------------------------------
    | Buyer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:buyer')->prefix('buyer')->name('buyer.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'buyer'])->name('dashboard');

        // Marketplace
        Route::get('/marketplace', [MarketplaceController::class, 'index'])->name('marketplace');
        Route::get('/marketplace/{product}', [MarketplaceController::class, 'show'])->name('marketplace.show');

        // Orders
        Route::get('/orders', [OrderController::class, 'buyerOrders'])->name('orders');
        Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    });

    /*
    |--------------------------------------------------------------------------
    | Supplier Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:supplier')->prefix('supplier')->name('supplier.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'supplier'])->name('dashboard');
        Route::get('/orders', [OrderController::class, 'supplierOrders'])->name('orders');
        Route::get('/inventory', [ProductController::class, 'inventory'])->name('inventory');
    });

    Route::get('/market-insights', [MarketInsightsController::class, 'index'])->name('market.insights');

    // Global Market Intelligence
    Route::get('/global-market', [GlobalMarketController::class, 'index'])->name('global.market');

    /*
    |--------------------------------------------------------------------------
    | Expert Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:expert')->prefix('expert')->name('expert.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'expert'])->name('dashboard');
        Route::get('/consultations', [DashboardController::class, 'consultations'])->name('consultations');
        Route::get('/knowledge', [DashboardController::class, 'knowledge'])->name('knowledge');
    });

    /*
    |--------------------------------------------------------------------------
    | Logistics Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:logistics')->prefix('logistics')->name('logistics.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'logistics'])->name('dashboard');
        Route::get('/deliveries', [DashboardController::class, 'deliveries'])->name('deliveries');
        Route::get('/routes', [DashboardController::class, 'routes'])->name('routes');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/market', [AdminController::class, 'market'])->name('market');
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        
        // Blockchain Admin Routes
        Route::get('/blockchain', [BlockchainController::class, 'adminDashboard'])->name('blockchain.dashboard');
        Route::get('/blockchain/transactions', [BlockchainController::class, 'getTransactions'])->name('blockchain.transactions');
        Route::get('/blockchain/products', [BlockchainController::class, 'getVerifiedProducts'])->name('blockchain.products');
        Route::get('/blockchain/farmers', [BlockchainController::class, 'getTopFarmers'])->name('blockchain.farmers');
    });
    
    /*
    |--------------------------------------------------------------------------
    | Blockchain Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth'])->prefix('blockchain')->name('blockchain.')->group(function () {
        // Product Verification (Farmer)
        Route::post('/verify-product/{product}', [BlockchainController::class, 'verifyProduct'])
            ->name('verify-product');
        Route::get('/product/{product}/verification', [BlockchainController::class, 'getProductVerification'])
            ->name('product-verification');
        
        // Farmer Reputation
        Route::post('/register-farmer', [BlockchainController::class, 'registerFarmer'])
            ->name('register-farmer');
        Route::get('/farmer/{user}/reputation', [BlockchainController::class, 'getFarmerReputation'])
            ->name('farmer-reputation');
        
        // Escrow Payments (Buyer)
        Route::post('/escrow/{order}/create', [BlockchainController::class, 'createEscrow'])
            ->name('create-escrow');
        Route::post('/escrow/{order}/confirm-delivery', [BlockchainController::class, 'confirmDelivery'])
            ->name('confirm-delivery');
    });
    
    // Public Blockchain API
    Route::prefix('api/blockchain')->group(function () {
        Route::get('/verify-batch', [BlockchainController::class, 'verifyByBatchId'])
            ->name('blockchain.verify-batch');
        Route::get('/stats', [BlockchainController::class, 'getStats'])
            ->name('blockchain.stats');
        Route::get('/verified-products', [BlockchainController::class, 'getVerifiedProducts'])
            ->name('blockchain.verified-products');
        Route::get('/top-farmers', [BlockchainController::class, 'getTopFarmers'])
            ->name('blockchain.top-farmers');
    });
});
