<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\FakeBlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    protected $blockchainService;
    
    public function __construct(FakeBlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }
    public function index()
    {
        $products = auth()->user()->products()
            ->latest()
            ->paginate(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'price_unit' => 'required|string',
            'quantity' => 'required|numeric|min:0',
            'quantity_unit' => 'required|string',
            'location' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'images.*' => 'nullable|image|max:2048',
            'is_organic' => 'boolean',
            'harvest_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after:harvest_date',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $images[] = Storage::url($path);
            }
        }

        $product = auth()->user()->products()->create([
            ...$validated,
            'images' => $images,
            'is_organic' => $request->boolean('is_organic'),
        ]);
        
        // Auto-verify on blockchain (demo mode)
        $this->blockchainService->autoVerifyProduct($product);

        return redirect()->route('farmer.products')
            ->with('success', 'Product created and verified on blockchain successfully! Batch ID: ' . $product->batch_id);
    }

    public function edit(Product $product)
    {
        $this->authorize('update', $product);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $this->authorize('update', $product);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0',
            'location' => 'required|string',
            'status' => 'required|in:available,sold,reserved',
        ]);

        $product->update($validated);

        return redirect()->route('farmer.products')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->authorize('delete', $product);

        $product->delete();

        return redirect()->route('farmer.products')
            ->with('success', 'Product deleted successfully.');
    }

    public function inventory()
    {
        $inventory = auth()->user()->products()
            ->where('status', 'available')
            ->sum('quantity');

        $lowStock = auth()->user()->products()
            ->where('quantity', '<', 10)
            ->get();

        return view('supplier.inventory', compact('inventory', 'lowStock'));
    }
}
