<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\BlockchainTransaction;
use App\Services\BlockchainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BlockchainController extends Controller
{
    protected $blockchainService;
    
    public function __construct(BlockchainService $blockchainService)
    {
        $this->blockchainService = $blockchainService;
    }
    
    /**
     * Verify product on blockchain (Farmer/Admin only)
     */
    public function verifyProduct(Request $request, Product $product)
    {
        $this->authorize('update', $product);
        
        $validated = $request->validate([
            'batch_id' => 'nullable|string|max:50',
            'ipfs_hash' => 'nullable|string|max:100',
        ]);
        
        if ($product->is_blockchain_verified) {
            return back()->with('error', 'Product already verified on blockchain');
        }
        
        $data = [
            'batch_id' => $validated['batch_id'] ?? null,
            'farmer_wallet' => $product->user->wallet_address,
            'ipfs_hash' => $validated['ipfs_hash'] ?? null,
        ];
        
        $transaction = $this->blockchainService->verifyProduct($product, $data);
        
        if ($transaction) {
            return back()->with('success', 'Product verified on blockchain successfully!');
        }
        
        return back()->with('error', 'Failed to verify product on blockchain');
    }
    
    /**
     * Get product blockchain verification details
     */
    public function getProductVerification(Product $product)
    {
        if (!$product->is_blockchain_verified) {
            return response()->json([
                'verified' => false,
                'message' => 'Product not verified on blockchain'
            ]);
        }
        
        $verification = $this->blockchainService->getProductVerification($product->batch_id);
        
        return response()->json([
            'verified' => true,
            'product' => $verification,
            'blockchain' => [
                'tx_hash' => $product->blockchain_tx_hash,
                'contract_address' => $product->verification_contract_address,
                'verified_at' => $product->blockchain_verified_at,
                'etherscan_url' => BlockchainTransaction::where('tx_hash', $product->blockchain_tx_hash)
                    ->first()?->etherscan_url,
            ]
        ]);
    }
    
    /**
     * Register farmer on blockchain
     */
    public function registerFarmer()
    {
        $user = Auth::user();
        
        if ($user->is_blockchain_registered) {
            return back()->with('error', 'Already registered on blockchain');
        }
        
        if ($user->role !== 'farmer') {
            return back()->with('error', 'Only farmers can register on blockchain');
        }
        
        $transaction = $this->blockchainService->registerFarmer($user);
        
        if ($transaction) {
            return back()->with('success', 'Registered on blockchain successfully! Your trust score is 500/1000');
        }
        
        return back()->with('error', 'Failed to register on blockchain');
    }
    
    /**
     * Get farmer blockchain reputation
     */
    public function getFarmerReputation(User $user)
    {
        if (!$user->is_blockchain_registered) {
            return response()->json([
                'registered' => false,
                'message' => 'Farmer not registered on blockchain'
            ]);
        }
        
        $reputation = $this->blockchainService->getFarmerReputation($user->wallet_address);
        
        return response()->json([
            'registered' => true,
            'reputation' => $reputation,
        ]);
    }
    
    /**
     * Create escrow payment for order (Buyer only)
     */
    public function createEscrow(Request $request, Order $order)
    {
        $this->authorize('update', $order);
        
        if ($order->buyer_id !== Auth::id()) {
            return back()->with('error', 'Only buyer can create escrow');
        }
        
        if ($order->is_escrow_payment) {
            return back()->with('error', 'Escrow already created for this order');
        }
        
        $validated = $request->validate([
            'buyer_wallet' => 'required|string|size:42|starts_with:0x',
            'farmer_wallet' => 'required|string|size:42|starts_with:0x',
            'amount_eth' => 'required|numeric|min:0.001',
        ]);
        
        $transaction = $this->blockchainService->createEscrow(
            $order,
            $validated['buyer_wallet'],
            $validated['farmer_wallet'],
            $validated['amount_eth']
        );
        
        if ($transaction) {
            return back()->with('success', 'Escrow payment created successfully!');
        }
        
        return back()->with('error', 'Failed to create escrow payment');
    }
    
    /**
     * Confirm delivery and release payment (Buyer only)
     */
    public function confirmDelivery(Order $order)
    {
        $this->authorize('update', $order);
        
        if ($order->buyer_id !== Auth::id()) {
            return back()->with('error', 'Only buyer can confirm delivery');
        }
        
        if (!$order->is_escrow_payment) {
            return back()->with('error', 'This order does not use escrow payment');
        }
        
        if ($order->escrow_status !== 'pending') {
            return back()->with('error', 'Delivery already confirmed or order cancelled');
        }
        
        $transaction = $this->blockchainService->confirmDelivery($order);
        
        if ($transaction) {
            // Release payment automatically or require admin
            $releaseTx = $this->blockchainService->releasePayment($order);
            
            return back()->with('success', 'Delivery confirmed and payment released to farmer!');
        }
        
        return back()->with('error', 'Failed to confirm delivery');
    }
    
    /**
     * Get blockchain statistics (Admin only)
     */
    public function getStats()
    {
        $this->authorize('admin');
        
        $stats = $this->blockchainService->getStats();
        
        return response()->json($stats);
    }
    
    /**
     * Get all blockchain transactions (Admin only)
     */
    public function getTransactions(Request $request)
    {
        $this->authorize('admin');
        
        $transactions = BlockchainTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return response()->json($transactions);
    }
    
    /**
     * Get verified products list (Public API)
     */
    public function getVerifiedProducts()
    {
        $products = Product::where('is_blockchain_verified', true)
            ->with('user:id,name')
            ->select('id', 'name', 'batch_id', 'user_id', 'location', 'blockchain_verified_at')
            ->orderBy('blockchain_verified_at', 'desc')
            ->paginate(20);
        
        return response()->json($products);
    }
    
    /**
     * Verify product by batch ID (Public API for consumers)
     */
    public function verifyByBatchId(Request $request)
    {
        $validated = $request->validate([
            'batch_id' => 'required|string|max:50',
        ]);
        
        $verification = $this->blockchainService->getProductVerification($validated['batch_id']);
        
        if (!$verification) {
            return response()->json([
                'verified' => false,
                'message' => 'Invalid batch ID or product not verified'
            ], 404);
        }
        
        return response()->json([
            'verified' => true,
            'verification' => $verification,
        ]);
    }
    
    /**
     * Admin dashboard view
     */
    public function adminDashboard()
    {
        $this->authorize('admin');
        
        $stats = $this->blockchainService->getStats();
        
        $recentTransactions = BlockchainTransaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        $verifiedProducts = Product::where('is_blockchain_verified', true)
            ->with('user:id,name')
            ->orderBy('blockchain_verified_at', 'desc')
            ->limit(10)
            ->get();
        
        $registeredFarmers = User::where('is_blockchain_registered', true)
            ->orderBy('blockchain_registered_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('admin.blockchain.dashboard', compact(
            'stats',
            'recentTransactions',
            'verifiedProducts',
            'registeredFarmers'
        ));
    }
    
    /**
     * Get top farmers by reputation
     */
    public function getTopFarmers()
    {
        $farmers = User::where('is_blockchain_registered', true)
            ->where('role', 'farmer')
            ->orderBy('blockchain_trust_score', 'desc')
            ->limit(20)
            ->get(['id', 'name', 'wallet_address', 'blockchain_trust_score', 'trust_tier', 'blockchain_total_transactions']);
        
        return response()->json($farmers);
    }
}
