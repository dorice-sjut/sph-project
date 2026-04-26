<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\Message;
use App\Models\MarketPrice;
use App\Services\FakeBlockchainService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $blockchainService;
    
    public function __construct(FakeBlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }

    public function farmer()
    {
        $user = auth()->user();

        $stats = [
            'products' => $user->products()->count(),
            'orders' => Order::where('seller_id', $user->id)->count(),
            'revenue' => Order::where('seller_id', $user->id)->sum('total_price'),
            'messages' => $user->receivedMessages()->unread()->count(),
        ];

        $recentOrders = Order::where('seller_id', $user->id)
            ->with(['buyer', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $myProducts = $user->products()
            ->latest()
            ->take(5)
            ->get();

        $marketPrices = MarketPrice::latestPrices()
            ->take(5)
            ->get();
        
        // Get blockchain activity for feed
        $blockchainActivities = $this->blockchainService->getActivityFeed(5);

        return view('dashboard.farmer', compact('stats', 'recentOrders', 'myProducts', 'marketPrices', 'blockchainActivities'));
    }

    public function buyer()
    {
        $user = auth()->user();

        $stats = [
            'orders' => Order::where('buyer_id', $user->id)->count(),
            'spent' => Order::where('buyer_id', $user->id)->sum('total_price'),
            'messages' => $user->receivedMessages()->unread()->count(),
        ];

        $recentOrders = Order::where('buyer_id', $user->id)
            ->with(['seller', 'product'])
            ->latest()
            ->take(5)
            ->get();

        $recommendations = Product::available()
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        $marketPrices = MarketPrice::latestPrices()
            ->take(5)
            ->get();

        return view('dashboard.buyer', compact('stats', 'recentOrders', 'recommendations', 'marketPrices'));
    }

    public function supplier()
    {
        $user = auth()->user();

        $stats = [
            'orders' => Order::where('seller_id', $user->id)->count(),
            'inventory' => $user->products()->sum('quantity'),
            'revenue' => Order::where('seller_id', $user->id)->sum('total_price'),
        ];

        return view('dashboard.supplier', compact('stats'));
    }

    public function expert()
    {
        $user = auth()->user();

        $stats = [
            'consultations' => Message::where('receiver_id', $user->id)->count(),
            'rating' => 4.8,
            'messages' => $user->receivedMessages()->unread()->count(),
        ];

        return view('dashboard.expert', compact('stats'));
    }

    public function logistics()
    {
        $user = auth()->user();

        $stats = [
            'deliveries' => Order::where('status', 'shipped')->count(),
            'completed' => Order::where('status', 'delivered')->count(),
            'active' => Order::where('status', 'shipped')->count(),
        ];

        return view('dashboard.logistics', compact('stats'));
    }

    public function consultations()
    {
        return view('expert.consultations');
    }

    public function knowledge()
    {
        return view('expert.knowledge');
    }

    public function deliveries()
    {
        return view('logistics.deliveries');
    }

    public function routes()
    {
        return view('logistics.routes');
    }

    public function switchRole(Request $request)
    {
        $request->validate([
            'role' => 'required|string|in:' . implode(',', \App\Models\User::ROLES),
        ]);

        $user = auth()->user();
        $newRole = $request->input('role');

        // Check if user has this role
        if (!$user->hasRole($newRole)) {
            return redirect()->back()->with('error', 'You do not have access to this role.');
        }

        // Switch the primary role
        if ($user->switchRole($newRole)) {
            return redirect()->route($newRole . '.dashboard')->with('success', 'Switched to ' . ucfirst($newRole) . ' dashboard.');
        }

        return redirect()->back()->with('error', 'Failed to switch role.');
    }
}
