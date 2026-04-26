<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Services\FakeBlockchainService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $blockchainService;
    
    public function __construct(FakeBlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }

    public function farmerOrders()
    {
        $orders = Order::where('seller_id', auth()->id())
            ->with(['buyer', 'product'])
            ->latest()
            ->paginate(10);

        return view('orders.farmer', compact('orders'));
    }

    public function buyerOrders()
    {
        $orders = Order::where('buyer_id', auth()->id())
            ->with(['seller', 'product'])
            ->latest()
            ->paginate(10);

        return view('orders.buyer', compact('orders'));
    }

    public function supplierOrders()
    {
        $orders = Order::whereHas('product', function ($q) {
                $q->where('user_id', auth()->id());
            })
            ->with(['buyer', 'product'])
            ->latest()
            ->paginate(10);

        return view('orders.supplier', compact('orders'));
    }

    public function show(Order $order)
    {
        $this->authorize('view', $order);

        $order->load(['buyer', 'seller', 'product']);

        return view('orders.show', compact('order'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|numeric|min:1',
            'delivery_address' => 'required|string',
        ]);

        $product = Product::findOrFail($validated['product_id']);

        // Check if enough quantity available
        if ($product->quantity < $validated['quantity']) {
            return back()->with('error', 'Insufficient quantity available.');
        }

        // Calculate total
        $totalPrice = $product->price * $validated['quantity'];

        $order = Order::create([
            'buyer_id' => auth()->id(),
            'seller_id' => $product->user_id,
            'product_id' => $product->id,
            'quantity' => $validated['quantity'],
            'total_price' => $totalPrice,
            'delivery_address' => $validated['delivery_address'],
            'status' => 'pending',
        ]);

        // Update product quantity
        $product->decrement('quantity', $validated['quantity']);
        
        // Create fake escrow for demo mode
        $this->blockchainService->createEscrow($order);

        return redirect()->route('buyer.orders')
            ->with('success', 'Order placed successfully with escrow payment! Funds are locked until delivery.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorize('update', $order);

        $validated = $request->validate([
            'status' => 'required|in:confirmed,shipped,delivered,cancelled',
        ]);

        $order->update(['status' => $validated['status']]);

        if ($validated['status'] === 'delivered') {
            $order->update(['delivered_at' => now()]);
            
            // Confirm delivery and release escrow payment
            $this->blockchainService->confirmDelivery($order);
        }

        return back()->with('success', 'Order status updated.');
    }
}
