<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Get featured products
        $featuredProducts = Product::available()
            ->with('user')
            ->latest()
            ->take(6)
            ->get();

        // Get market insights
        $marketInsights = MarketPrice::latestPrices()
            ->take(5)
            ->get();

        return view('landing', compact('featuredProducts', 'marketInsights'));
    }
}
