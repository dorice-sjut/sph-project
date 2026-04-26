<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'users' => User::count(),
            'farmers' => User::where('role', 'farmer')->count(),
            'buyers' => User::where('role', 'buyer')->count(),
            'products' => Product::count(),
            'orders' => Order::count(),
            'revenue' => Order::sum('total_price'),
        ];

        $recentUsers = User::latest()->take(5)->get();
        $recentOrders = Order::with(['buyer', 'seller', 'product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentOrders'));
    }

    public function users(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(20);
        $roles = User::ROLES;

        return view('admin.users', compact('users', 'roles'));
    }

    public function products()
    {
        $products = Product::with('user')->latest()->paginate(20);
        return view('admin.products', compact('products'));
    }

    public function orders()
    {
        $orders = Order::with(['buyer', 'seller', 'product'])->latest()->paginate(20);
        return view('admin.orders', compact('orders'));
    }

    public function market()
    {
        $prices = MarketPrice::latest()->paginate(20);
        return view('admin.market', compact('prices'));
    }

    public function settings()
    {
        return view('admin.settings');
    }
}
