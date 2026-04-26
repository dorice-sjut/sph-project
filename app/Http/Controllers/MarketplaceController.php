<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\MarketPrice;
use Illuminate\Http\Request;

class MarketplaceController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::available()->with('user');

        // Category filter
        if ($request->filled('category')) {
            $query->byCategory($request->category);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Location filter
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Sort
        $sort = $request->get('sort', 'latest');
        match($sort) {
            'price_low' => $query->orderBy('price', 'asc'),
            'price_high' => $query->orderBy('price', 'desc'),
            default => $query->latest(),
        };

        $products = $query->paginate(12);
        $categories = Product::distinct()->pluck('category');

        // Get recommended market prices
        $marketPrices = MarketPrice::latestPrices()
            ->take(4)
            ->get();

        return view('marketplace.index', compact('products', 'categories', 'marketPrices'));
    }

    public function show(Product $product)
    {
        $product->load('user');

        // Get related products
        $related = Product::available()
            ->where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        // Get market price for this product category
        $marketPrice = MarketPrice::where('category', $product->category)
            ->latestPrices()
            ->first();

        return view('marketplace.show', compact('product', 'related', 'marketPrice'));
    }
}
