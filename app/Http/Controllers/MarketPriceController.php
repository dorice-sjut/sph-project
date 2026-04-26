<?php

namespace App\Http\Controllers;

use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketPriceController extends Controller
{
    public function index(Request $request)
    {
        $query = MarketPrice::latestPrices();

        // Country filter
        if ($request->filled('country')) {
            $query->byCountry($request->country);
        }

        // Category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        $prices = $query->paginate(20);

        // Get unique countries and categories for filters
        $countries = MarketPrice::distinct()->pluck('country');
        $categories = MarketPrice::distinct()->pluck('category');

        // Get price trends data for chart
        $trends = MarketPrice::select('category')
            ->selectRaw('AVG(price_usd) as avg_price, DATE(price_date) as date')
            ->groupBy('category', 'date')
            ->latest('date')
            ->take(30)
            ->get();

        return view('market_prices.index', compact('prices', 'countries', 'categories', 'trends'));
    }

    public function show($product)
    {
        $price = MarketPrice::where('product_name', 'like', '%' . $product . '%')
            ->latestPrices()
            ->first();

        if (!$price) {
            abort(404);
        }

        // Get historical data
        $history = MarketPrice::where('product_name', $price->product_name)
            ->where('country', $price->country)
            ->orderBy('price_date', 'asc')
            ->take(30)
            ->get();

        return view('market_prices.show', compact('price', 'history'));
    }
}
