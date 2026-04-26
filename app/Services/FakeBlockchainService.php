<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use App\Models\BlockchainTransaction;
use Illuminate\Support\Str;
use Carbon\Carbon;

/**
 * Fake Blockchain Service for Demo Mode
 * Generates realistic blockchain data without actual blockchain connection
 */
class FakeBlockchainService
{
    protected $demoMode = true;
    protected $network = 'sepolia';
    
    /**
     * Generate fake Ethereum address
     */
    public function generateAddress(): string
    {
        return '0x' . Str::random(40);
    }
    
    /**
     * Generate fake transaction hash
     */
    public function generateTxHash(): string
    {
        return '0x' . Str::random(64);
    }
    
    /**
     * Generate fake batch ID
     */
    public function generateBatchId(): string
    {
        $prefix = ['AGRO', 'FARM', 'CROP', 'FRESH'][rand(0, 3)];
        $year = date('Y');
        $random = strtoupper(Str::random(6));
        return "{$prefix}-{$year}-{$random}";
    }
    
    /**
     * Auto-verify product when created (for demo)
     */
    public function autoVerifyProduct(Product $product): void
    {
        if ($product->is_blockchain_verified) {
            return;
        }
        
        $batchId = $this->generateBatchId();
        $txHash = $this->generateTxHash();
        $contractAddress = $this->generateAddress();
        
        // Update product
        $product->update([
            'is_blockchain_verified' => true,
            'batch_id' => $batchId,
            'blockchain_tx_hash' => $txHash,
            'verification_contract_address' => $contractAddress,
            'blockchain_verified_at' => now(),
        ]);
        
        // Create transaction record
        BlockchainTransaction::create([
            'tx_hash' => $txHash,
            'contract_name' => 'AgroSphereOrigin',
            'contract_address' => $contractAddress,
            'function_name' => 'verifyProduct',
            'parameters' => [
                'product_id' => $product->id,
                'batch_id' => $batchId,
                'farmer' => $product->user->name,
                'region' => $product->location,
            ],
            'status' => 'confirmed',
            'block_number' => rand(4000000, 5000000),
            'gas_used' => rand(45000, 55000),
            'gas_price' => '20000000000',
            'from_address' => $this->generateAddress(),
            'to_address' => $contractAddress,
            'value' => 0,
            'user_id' => $product->user_id,
            'related_type' => Product::class,
            'related_id' => $product->id,
            'confirmed_at' => now(),
            'confirmations' => 12,
        ]);
        
        // Log activity
        $this->logActivity('Product Verified', "Product #{$product->id} verified on blockchain", 'success');
    }
    
    /**
     * Create fake escrow for order
     */
    public function createEscrow(Order $order): void
    {
        if ($order->is_escrow_payment) {
            return;
        }
        
        $txHash = $this->generateTxHash();
        $contractAddress = $this->generateAddress();
        $buyerWallet = $this->generateAddress();
        $farmerWallet = $this->generateAddress();
        
        // Calculate amounts
        $amount = $order->total_price / 2000; // Convert to ETH (approx $2000/ETH)
        $platformFee = $amount * 0.025; // 2.5% fee
        $farmerAmount = $amount - $platformFee;
        
        $order->update([
            'is_escrow_payment' => true,
            'escrow_contract_address' => $contractAddress,
            'escrow_tx_hash' => $txHash,
            'escrow_status' => 'pending',
            'escrow_created_at' => now(),
            'escrow_amount_eth' => $farmerAmount,
            'platform_fee_eth' => $platformFee,
            'buyer_wallet_address' => $buyerWallet,
            'farmer_wallet_address' => $farmerWallet,
            'payment_status' => 'paid', // Funds locked in escrow
        ]);
        
        // Create transaction record
        BlockchainTransaction::create([
            'tx_hash' => $txHash,
            'contract_name' => 'AgroSphereEscrow',
            'contract_address' => $contractAddress,
            'function_name' => 'createEscrow',
            'parameters' => [
                'order_id' => $order->id,
                'buyer' => $buyerWallet,
                'farmer' => $farmerWallet,
                'amount' => $amount,
            ],
            'status' => 'confirmed',
            'block_number' => rand(4000000, 5000000),
            'gas_used' => rand(80000, 100000),
            'gas_price' => '20000000000',
            'from_address' => $buyerWallet,
            'to_address' => $contractAddress,
            'value' => $amount,
            'user_id' => $order->buyer_id,
            'related_type' => Order::class,
            'related_id' => $order->id,
            'confirmed_at' => now(),
            'confirmations' => 12,
        ]);
        
        $this->logActivity('Escrow Created', "Order #{$order->id} - Funds locked: " . number_format($amount, 4) . " ETH", 'pending');
    }
    
    /**
     * Confirm delivery and release payment
     */
    public function confirmDelivery(Order $order): void
    {
        if (!$order->is_escrow_payment || $order->escrow_status !== 'pending') {
            return;
        }
        
        $txHash = $this->generateTxHash();
        
        $order->update([
            'escrow_status' => 'released',
            'escrow_released_at' => now(),
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
        
        // Create release transaction
        BlockchainTransaction::create([
            'tx_hash' => $txHash,
            'contract_name' => 'AgroSphereEscrow',
            'contract_address' => $order->escrow_contract_address,
            'function_name' => 'releasePayment',
            'parameters' => [
                'order_id' => $order->id,
                'farmer' => $order->farmer_wallet_address,
                'amount' => $order->escrow_amount_eth,
            ],
            'status' => 'confirmed',
            'block_number' => rand(4000000, 5000000),
            'gas_used' => rand(30000, 40000),
            'gas_price' => '20000000000',
            'from_address' => $order->escrow_contract_address,
            'to_address' => $order->farmer_wallet_address,
            'value' => $order->escrow_amount_eth,
            'user_id' => $order->seller_id,
            'related_type' => Order::class,
            'related_id' => $order->id,
            'confirmed_at' => now(),
            'confirmations' => 12,
        ]);
        
        // Update farmer reputation
        $this->updateFarmerReputation($order->seller, true);
        
        $this->logActivity('Payment Released', "Order #{$order->id} - " . number_format($order->escrow_amount_eth, 4) . " ETH sent to farmer", 'success');
    }
    
    /**
     * Update farmer trust score
     */
    public function updateFarmerReputation(User $farmer, bool $success): void
    {
        if (!$farmer->is_blockchain_registered) {
            // Auto-register farmer
            $farmer->update([
                'is_blockchain_registered' => true,
                'wallet_address' => $this->generateAddress(),
                'blockchain_trust_score' => 500,
                'trust_tier' => 'Average',
                'blockchain_registered_at' => now(),
            ]);
        }
        
        // Update stats
        $farmer->increment('blockchain_total_transactions');
        if ($success) {
            $farmer->increment('blockchain_successful_deliveries');
        } else {
            $farmer->increment('blockchain_failed_deliveries');
        }
        
        // Calculate new trust score
        $total = $farmer->blockchain_total_transactions;
        $successful = $farmer->blockchain_successful_deliveries;
        
        if ($total > 0) {
            $score = (int) (($successful / $total) * 1000);
            $score = max(0, min(1000, $score));
        } else {
            $score = 500;
        }
        
        // Determine tier
        $tier = 'Poor';
        if ($score >= 900) $tier = 'Excellent';
        elseif ($score >= 750) $tier = 'Good';
        elseif ($score >= 500) $tier = 'Average';
        
        $farmer->update([
            'blockchain_trust_score' => $score,
            'trust_tier' => $tier,
        ]);
    }
    
    /**
     * Get blockchain activity feed
     */
    public function getActivityFeed(int $limit = 10): array
    {
        try {
            $transactions = BlockchainTransaction::with(['user', 'related'])
                ->orderBy('created_at', 'desc')
                ->limit($limit)
                ->get();
        } catch (\Exception $e) {
            // Table doesn't exist yet, return empty array
            return [];
        }
        
        $activities = [];
        
        foreach ($transactions as $tx) {
            $activities[] = [
                'id' => $tx->id,
                'type' => $this->getActivityType($tx->function_name),
                'title' => $this->getActivityTitle($tx),
                'description' => $this->getActivityDescription($tx),
                'tx_hash' => $tx->tx_hash,
                'status' => $tx->status,
                'timestamp' => $tx->created_at,
                'user' => $tx->user?->name ?? 'Unknown',
                'icon' => $this->getActivityIcon($tx->function_name),
                'color' => $this->getActivityColor($tx->status),
            ];
        }
        
        return $activities;
    }
    
    /**
     * Get blockchain statistics
     */
    public function getStats(): array
    {
        try {
            return [
                'verified_products' => Product::where('is_blockchain_verified', true)->count(),
                'registered_farmers' => User::where('is_blockchain_registered', true)->count(),
                'escrow_orders' => Order::where('is_escrow_payment', true)->count(),
                'total_transactions' => BlockchainTransaction::count(),
                'pending_escrow' => Order::where('is_escrow_payment', true)->where('escrow_status', 'pending')->count(),
                'released_payments' => Order::where('is_escrow_payment', true)->where('escrow_status', 'released')->count(),
                'total_eth_locked' => Order::where('is_escrow_payment', true)->sum('escrow_amount_eth') ?? 0,
                'avg_trust_score' => User::where('is_blockchain_registered', true)->avg('blockchain_trust_score') ?? 0,
            ];
        } catch (\Exception $e) {
            return [
                'verified_products' => 0,
                'registered_farmers' => 0,
                'escrow_orders' => 0,
                'total_transactions' => 0,
                'pending_escrow' => 0,
                'released_payments' => 0,
                'total_eth_locked' => 0,
                'avg_trust_score' => 0,
            ];
        }
    }
    
    /**
     * Get top farmers by trust score
     */
    public function getTopFarmers(int $limit = 10): array
    {
        return User::where('is_blockchain_registered', true)
            ->where('role', 'farmer')
            ->orderBy('blockchain_trust_score', 'desc')
            ->limit($limit)
            ->get(['id', 'name', 'wallet_address', 'blockchain_trust_score', 'trust_tier', 'blockchain_total_transactions', 'blockchain_successful_deliveries'])
            ->toArray();
    }
    
    /**
     * Helper methods for activity feed
     */
    protected function getActivityType(string $function): string
    {
        return match($function) {
            'verifyProduct' => 'verification',
            'createEscrow' => 'escrow',
            'releasePayment' => 'payment',
            'registerFarmer' => 'registration',
            default => 'transaction',
        };
    }
    
    protected function getActivityTitle(BlockchainTransaction $tx): string
    {
        return match($tx->function_name) {
            'verifyProduct' => 'Product Verified',
            'createEscrow' => 'Escrow Created',
            'releasePayment' => 'Payment Released',
            'registerFarmer' => 'Farmer Registered',
            default => 'Blockchain Transaction',
        };
    }
    
    protected function getActivityDescription(BlockchainTransaction $tx): string
    {
        $params = $tx->parameters;
        
        return match($tx->function_name) {
            'verifyProduct' => "Batch: {$params['batch_id']}",
            'createEscrow' => "Locked: " . number_format($tx->value, 4) . " ETH",
            'releasePayment' => "Released: " . number_format($params['amount'] ?? 0, 4) . " ETH",
            'registerFarmer' => "Wallet: " . substr($params['farmer'] ?? '', 0, 10) . "...",
            default => "TX: " . substr($tx->tx_hash, 0, 10) . "...",
        };
    }
    
    protected function getActivityIcon(string $function): string
    {
        return match($function) {
            'verifyProduct' => 'verified',
            'createEscrow' => 'lock',
            'releasePayment' => 'payments',
            'registerFarmer' => 'person_add',
            default => 'receipt',
        };
    }
    
    protected function getActivityColor(string $status): string
    {
        return match($status) {
            'confirmed' => 'emerald',
            'pending' => 'amber',
            'failed' => 'red',
            default => 'slate',
        };
    }
    
    /**
     * Log activity (placeholder for activity logging)
     */
    protected function logActivity(string $action, string $details, string $status): void
    {
        // In production, this could write to an activity log
        \Log::info("[Blockchain Demo] {$action}: {$details}");
    }
}
