<?php

namespace App\Services;

use App\Models\BlockchainTransaction;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Web3\Web3;
use Web3\Contract;
use Web3\Providers\HttpProvider;
use Web3\RequestManagers\HttpRequestManager;

class BlockchainService
{
    protected $web3;
    protected $contract;
    protected $network;
    protected $walletAddress;
    protected $privateKey;
    protected $enabled;
    
    public function __construct()
    {
        $this->enabled = config('blockchain.enabled', false);
        $this->network = config('blockchain.network', 'sepolia');
        $this->walletAddress = config('blockchain.wallet.address');
        $this->privateKey = config('blockchain.wallet.private_key');
        
        if ($this->enabled && $this->walletAddress) {
            $this->initializeWeb3();
        }
    }
    
    /**
     * Initialize Web3 connection
     */
    protected function initializeWeb3(): void
    {
        $rpcUrl = $this->getRpcUrl();
        
        try {
            $requestManager = new HttpRequestManager($rpcUrl, 30);
            $provider = new HttpProvider($requestManager);
            $this->web3 = new Web3($provider);
        } catch (\Exception $e) {
            Log::error('Failed to initialize Web3: ' . $e->getMessage());
            $this->enabled = false;
        }
    }
    
    /**
     * Get RPC URL for current network
     */
    protected function getRpcUrl(): string
    {
        $alchemyKey = config('blockchain.alchemy_key');
        $baseUrl = config("blockchain.rpc_urls.{$this->network}");
        
        if ($alchemyKey && strpos($baseUrl, 'alchemy.com') !== false) {
            return $baseUrl . $alchemyKey;
        }
        
        return $baseUrl;
    }
    
    /**
     * Verify product on blockchain
     */
    public function verifyProduct(Product $product, array $data): ?BlockchainTransaction
    {
        if (!$this->enabled) {
            Log::warning('Blockchain not enabled, simulating verification');
            return $this->simulateVerification($product, $data);
        }
        
        try {
            $contractAddress = config('blockchain.contracts.origin.address');
            $batchId = $data['batch_id'] ?? 'BATCH-' . strtoupper(uniqid());
            
            // Create transaction record
            $transaction = BlockchainTransaction::create([
                'contract_name' => 'AgroSphereOrigin',
                'contract_address' => $contractAddress,
                'function_name' => 'verifyProduct',
                'parameters' => [
                    'product_id' => $product->id,
                    'farmer' => $data['farmer_wallet'] ?? $this->walletAddress,
                    'farmer_name' => $product->user->name,
                    'region' => $product->location,
                    'batch_id' => $batchId,
                    'harvest_date' => $product->harvest_date?->timestamp ?? now()->timestamp,
                    'product_name' => $product->name,
                    'category' => $product->category,
                    'is_organic' => $product->is_organic,
                ],
                'status' => 'pending',
                'from_address' => $this->walletAddress,
                'to_address' => $contractAddress,
                'user_id' => $product->user_id,
                'related_type' => Product::class,
                'related_id' => $product->id,
            ]);
            
            // In production, this would call the actual smart contract
            // For now, simulate success
            $this->simulateTransactionConfirmation($transaction);
            
            // Update product
            $product->update([
                'is_blockchain_verified' => true,
                'batch_id' => $batchId,
                'blockchain_tx_hash' => $transaction->tx_hash,
                'verification_contract_address' => $contractAddress,
                'blockchain_verified_at' => now(),
                'ipfs_hash' => $data['ipfs_hash'] ?? null,
            ]);
            
            return $transaction;
            
        } catch (\Exception $e) {
            Log::error('Product verification failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create escrow payment for order
     */
    public function createEscrow(Order $order, string $buyerWallet, string $farmerWallet, float $amount): ?BlockchainTransaction
    {
        if (!$this->enabled) {
            return $this->simulateEscrow($order, $buyerWallet, $farmerWallet, $amount);
        }
        
        try {
            $contractAddress = config('blockchain.contracts.escrow.address');
            $platformFee = ($amount * config('blockchain.platform.fee_percentage', 2.5)) / 100;
            $farmerAmount = $amount - $platformFee;
            
            $transaction = BlockchainTransaction::create([
                'contract_name' => 'AgroSphereEscrow',
                'contract_address' => $contractAddress,
                'function_name' => 'createEscrow',
                'parameters' => [
                    'order_id' => $order->id,
                    'buyer' => $buyerWallet,
                    'farmer' => $farmerWallet,
                    'product_name' => $order->product->name ?? 'Unknown',
                    'quantity' => $order->quantity,
                ],
                'status' => 'pending',
                'from_address' => $buyerWallet,
                'to_address' => $contractAddress,
                'value' => $amount,
                'user_id' => $order->buyer_id,
                'related_type' => Order::class,
                'related_id' => $order->id,
            ]);
            
            $this->simulateTransactionConfirmation($transaction);
            
            $order->update([
                'is_escrow_payment' => true,
                'escrow_contract_address' => $contractAddress,
                'escrow_tx_hash' => $transaction->tx_hash,
                'escrow_status' => 'pending',
                'escrow_created_at' => now(),
                'escrow_amount_eth' => $farmerAmount,
                'platform_fee_eth' => $platformFee,
                'buyer_wallet_address' => $buyerWallet,
                'farmer_wallet_address' => $farmerWallet,
            ]);
            
            return $transaction;
            
        } catch (\Exception $e) {
            Log::error('Escrow creation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Confirm delivery and release payment
     */
    public function confirmDelivery(Order $order): ?BlockchainTransaction
    {
        if (!$this->enabled) {
            return $this->simulateConfirmDelivery($order);
        }
        
        try {
            $contractAddress = config('blockchain.contracts.escrow.address');
            
            $transaction = BlockchainTransaction::create([
                'contract_name' => 'AgroSphereEscrow',
                'contract_address' => $contractAddress,
                'function_name' => 'confirmDelivery',
                'parameters' => [
                    'order_id' => $order->id,
                ],
                'status' => 'pending',
                'from_address' => $order->buyer_wallet_address,
                'to_address' => $contractAddress,
                'user_id' => $order->buyer_id,
                'related_type' => Order::class,
                'related_id' => $order->id,
            ]);
            
            $this->simulateTransactionConfirmation($transaction);
            
            $order->update([
                'escrow_status' => 'delivered',
                'escrow_delivered_at' => now(),
                'status' => 'delivered',
            ]);
            
            // Update farmer reputation
            $this->updateFarmerReputation($order->seller, true);
            
            return $transaction;
            
        } catch (\Exception $e) {
            Log::error('Delivery confirmation failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Release payment to farmer
     */
    public function releasePayment(Order $order): ?BlockchainTransaction
    {
        if (!$this->enabled) {
            return $this->simulateReleasePayment($order);
        }
        
        try {
            $contractAddress = config('blockchain.contracts.escrow.address');
            
            $transaction = BlockchainTransaction::create([
                'contract_name' => 'AgroSphereEscrow',
                'contract_address' => $contractAddress,
                'function_name' => 'releasePayment',
                'parameters' => [
                    'order_id' => $order->id,
                ],
                'status' => 'pending',
                'from_address' => $this->walletAddress,
                'to_address' => $contractAddress,
                'user_id' => $order->seller_id,
                'related_type' => Order::class,
                'related_id' => $order->id,
            ]);
            
            $this->simulateTransactionConfirmation($transaction);
            
            $order->update([
                'escrow_status' => 'released',
                'escrow_released_at' => now(),
                'payment_status' => 'paid',
            ]);
            
            return $transaction;
            
        } catch (\Exception $e) {
            Log::error('Payment release failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Register farmer on blockchain reputation system
     */
    public function registerFarmer(User $user): ?BlockchainTransaction
    {
        if (!$this->enabled) {
            return $this->simulateFarmerRegistration($user);
        }
        
        try {
            $contractAddress = config('blockchain.contracts.reputation.address');
            $walletAddress = $user->wallet_address ?? $this->generateWalletAddress($user);
            
            $transaction = BlockchainTransaction::create([
                'contract_name' => 'AgroSphereReputation',
                'contract_address' => $contractAddress,
                'function_name' => 'registerFarmer',
                'parameters' => [
                    'farmer' => $walletAddress,
                    'name' => $user->name,
                    'region' => $user->location ?? 'Unknown',
                ],
                'status' => 'pending',
                'from_address' => $this->walletAddress,
                'to_address' => $contractAddress,
                'user_id' => $user->id,
                'related_type' => User::class,
                'related_id' => $user->id,
            ]);
            
            $this->simulateTransactionConfirmation($transaction);
            
            $user->update([
                'is_blockchain_registered' => true,
                'wallet_address' => $walletAddress,
                'reputation_contract_address' => $contractAddress,
                'blockchain_registered_at' => now(),
            ]);
            
            return $transaction;
            
        } catch (\Exception $e) {
            Log::error('Farmer registration failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Update farmer reputation after transaction
     */
    public function updateFarmerReputation(User $farmer, bool $success): ?BlockchainTransaction
    {
        if (!$this->enabled) {
            return $this->simulateReputationUpdate($farmer, $success);
        }
        
        try {
            $contractAddress = config('blockchain.contracts.reputation.address');
            
            $function = $success ? 'recordSuccessfulTransaction' : 'recordFailedTransaction';
            
            $transaction = BlockchainTransaction::create([
                'contract_name' => 'AgroSphereReputation',
                'contract_address' => $contractAddress,
                'function_name' => $function,
                'parameters' => [
                    'farmer' => $farmer->wallet_address,
                ],
                'status' => 'pending',
                'from_address' => $this->walletAddress,
                'to_address' => $contractAddress,
                'user_id' => $farmer->id,
                'related_type' => User::class,
                'related_id' => $farmer->id,
            ]);
            
            $this->simulateTransactionConfirmation($transaction);
            
            // Update local stats
            $farmer->increment('blockchain_total_transactions');
            if ($success) {
                $farmer->increment('blockchain_successful_deliveries');
            } else {
                $farmer->increment('blockchain_failed_deliveries');
            }
            
            // Calculate new trust score
            $this->calculateTrustScore($farmer);
            
            return $transaction;
            
        } catch (\Exception $e) {
            Log::error('Reputation update failed: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get product verification from blockchain
     */
    public function getProductVerification(string $batchId): ?array
    {
        // In production, this would call the contract's getProductByBatch function
        // For now, return from database
        $product = Product::where('batch_id', $batchId)
            ->where('is_blockchain_verified', true)
            ->first();
        
        if (!$product) {
            return null;
        }
        
        return [
            'product_id' => $product->id,
            'batch_id' => $product->batch_id,
            'farmer_name' => $product->user->name,
            'region' => $product->location,
            'harvest_date' => $product->harvest_date?->toDateString(),
            'product_name' => $product->name,
            'category' => $product->category,
            'is_organic' => $product->is_organic,
            'is_verified' => true,
            'verified_at' => $product->blockchain_verified_at,
            'tx_hash' => $product->blockchain_tx_hash,
        ];
    }
    
    /**
     * Get farmer reputation from blockchain
     */
    public function getFarmerReputation(string $walletAddress): ?array
    {
        $user = User::where('wallet_address', $walletAddress)->first();
        
        if (!$user) {
            return null;
        }
        
        return [
            'address' => $walletAddress,
            'name' => $user->name,
            'trust_score' => $user->blockchain_trust_score,
            'trust_tier' => $user->trust_tier,
            'total_transactions' => $user->blockchain_total_transactions,
            'successful_deliveries' => $user->blockchain_successful_deliveries,
            'failed_deliveries' => $user->blockchain_failed_deliveries,
            'is_verified' => $user->is_blockchain_registered,
        ];
    }
    
    /**
     * Check if blockchain is enabled
     */
    public function isEnabled(): bool
    {
        return $this->enabled;
    }
    
    /**
     * Get blockchain stats
     */
    public function getStats(): array
    {
        return [
            'enabled' => $this->enabled,
            'network' => $this->network,
            'wallet' => $this->walletAddress,
            'total_transactions' => BlockchainTransaction::count(),
            'confirmed_transactions' => BlockchainTransaction::confirmed()->count(),
            'pending_transactions' => BlockchainTransaction::pending()->count(),
            'verified_products' => Product::where('is_blockchain_verified', true)->count(),
            'registered_farmers' => User::where('is_blockchain_registered', true)->count(),
            'escrow_orders' => Order::where('is_escrow_payment', true)->count(),
        ];
    }
    
    // Simulation methods for testing without actual blockchain
    
    protected function simulateVerification(Product $product, array $data): BlockchainTransaction
    {
        $batchId = $data['batch_id'] ?? 'BATCH-' . strtoupper(uniqid());
        
        $transaction = BlockchainTransaction::create([
            'tx_hash' => '0x' . str_repeat('0', 64),
            'contract_name' => 'AgroSphereOrigin',
            'contract_address' => '0x' . str_repeat('0', 40),
            'function_name' => 'verifyProduct',
            'parameters' => ['product_id' => $product->id, 'batch_id' => $batchId],
            'status' => 'confirmed',
            'from_address' => $this->walletAddress ?? '0x' . str_repeat('0', 40),
            'to_address' => '0x' . str_repeat('0', 40),
            'confirmed_at' => now(),
            'confirmations' => 12,
            'user_id' => $product->user_id,
            'related_type' => Product::class,
            'related_id' => $product->id,
        ]);
        
        $product->update([
            'is_blockchain_verified' => true,
            'batch_id' => $batchId,
            'blockchain_tx_hash' => $transaction->tx_hash,
            'blockchain_verified_at' => now(),
        ]);
        
        return $transaction;
    }
    
    protected function simulateTransactionConfirmation(BlockchainTransaction $transaction): void
    {
        $txHash = '0x' . bin2hex(random_bytes(32));
        
        $transaction->update([
            'tx_hash' => $txHash,
            'status' => 'confirmed',
            'block_number' => rand(1000000, 9999999),
            'gas_used' => rand(50000, 200000),
            'confirmed_at' => now(),
            'confirmations' => 12,
        ]);
    }
    
    protected function simulateEscrow(Order $order, string $buyerWallet, string $farmerWallet, float $amount): BlockchainTransaction
    {
        $transaction = BlockchainTransaction::create([
            'tx_hash' => '0x' . str_repeat('0', 64),
            'contract_name' => 'AgroSphereEscrow',
            'contract_address' => '0x' . str_repeat('0', 40),
            'function_name' => 'createEscrow',
            'parameters' => ['order_id' => $order->id],
            'status' => 'confirmed',
            'from_address' => $buyerWallet,
            'to_address' => '0x' . str_repeat('0', 40),
            'value' => $amount,
            'confirmed_at' => now(),
            'confirmations' => 12,
            'user_id' => $order->buyer_id,
            'related_type' => Order::class,
            'related_id' => $order->id,
        ]);
        
        $order->update([
            'is_escrow_payment' => true,
            'escrow_tx_hash' => $transaction->tx_hash,
            'escrow_status' => 'pending',
            'escrow_created_at' => now(),
            'buyer_wallet_address' => $buyerWallet,
            'farmer_wallet_address' => $farmerWallet,
        ]);
        
        return $transaction;
    }
    
    protected function simulateConfirmDelivery(Order $order): BlockchainTransaction
    {
        $transaction = BlockchainTransaction::create([
            'tx_hash' => '0x' . str_repeat('0', 64),
            'contract_name' => 'AgroSphereEscrow',
            'contract_address' => '0x' . str_repeat('0', 40),
            'function_name' => 'confirmDelivery',
            'parameters' => ['order_id' => $order->id],
            'status' => 'confirmed',
            'from_address' => $order->buyer_wallet_address,
            'to_address' => '0x' . str_repeat('0', 40),
            'confirmed_at' => now(),
            'confirmations' => 12,
            'user_id' => $order->buyer_id,
            'related_type' => Order::class,
            'related_id' => $order->id,
        ]);
        
        $order->update([
            'escrow_status' => 'delivered',
            'escrow_delivered_at' => now(),
            'status' => 'delivered',
        ]);
        
        return $transaction;
    }
    
    protected function simulateReleasePayment(Order $order): BlockchainTransaction
    {
        $transaction = BlockchainTransaction::create([
            'tx_hash' => '0x' . str_repeat('0', 64),
            'contract_name' => 'AgroSphereEscrow',
            'contract_address' => '0x' . str_repeat('0', 40),
            'function_name' => 'releasePayment',
            'parameters' => ['order_id' => $order->id],
            'status' => 'confirmed',
            'from_address' => $this->walletAddress ?? '0x' . str_repeat('0', 40),
            'to_address' => $order->farmer_wallet_address,
            'confirmed_at' => now(),
            'confirmations' => 12,
            'user_id' => $order->seller_id,
            'related_type' => Order::class,
            'related_id' => $order->id,
        ]);
        
        $order->update([
            'escrow_status' => 'released',
            'escrow_released_at' => now(),
            'payment_status' => 'paid',
        ]);
        
        return $transaction;
    }
    
    protected function simulateFarmerRegistration(User $user): BlockchainTransaction
    {
        $walletAddress = $this->generateWalletAddress($user);
        
        $transaction = BlockchainTransaction::create([
            'tx_hash' => '0x' . str_repeat('0', 64),
            'contract_name' => 'AgroSphereReputation',
            'contract_address' => '0x' . str_repeat('0', 40),
            'function_name' => 'registerFarmer',
            'parameters' => ['farmer' => $walletAddress, 'name' => $user->name],
            'status' => 'confirmed',
            'from_address' => $this->walletAddress ?? '0x' . str_repeat('0', 40),
            'to_address' => '0x' . str_repeat('0', 40),
            'confirmed_at' => now(),
            'confirmations' => 12,
            'user_id' => $user->id,
            'related_type' => User::class,
            'related_id' => $user->id,
        ]);
        
        $user->update([
            'is_blockchain_registered' => true,
            'wallet_address' => $walletAddress,
            'blockchain_trust_score' => 500,
            'trust_tier' => 'Average',
            'blockchain_registered_at' => now(),
        ]);
        
        return $transaction;
    }
    
    protected function simulateReputationUpdate(User $farmer, bool $success): BlockchainTransaction
    {
        $function = $success ? 'recordSuccessfulTransaction' : 'recordFailedTransaction';
        
        $transaction = BlockchainTransaction::create([
            'tx_hash' => '0x' . str_repeat('0', 64),
            'contract_name' => 'AgroSphereReputation',
            'contract_address' => '0x' . str_repeat('0', 40),
            'function_name' => $function,
            'parameters' => ['farmer' => $farmer->wallet_address],
            'status' => 'confirmed',
            'from_address' => $this->walletAddress ?? '0x' . str_repeat('0', 40),
            'to_address' => '0x' . str_repeat('0', 40),
            'confirmed_at' => now(),
            'confirmations' => 12,
            'user_id' => $farmer->id,
            'related_type' => User::class,
            'related_id' => $farmer->id,
        ]);
        
        $farmer->increment('blockchain_total_transactions');
        if ($success) {
            $farmer->increment('blockchain_successful_deliveries');
        } else {
            $farmer->increment('blockchain_failed_deliveries');
        }
        
        $this->calculateTrustScore($farmer);
        
        return $transaction;
    }
    
    protected function generateWalletAddress(User $user): string
    {
        return '0x' . substr(hash('sha256', $user->id . $user->email . 'agrosphere'), 0, 40);
    }
    
    protected function calculateTrustScore(User $farmer): void
    {
        $total = $farmer->blockchain_total_transactions;
        
        if ($total === 0) {
            $score = 500;
        } else {
            $successRate = ($farmer->blockchain_successful_deliveries / $total) * 1000;
            $score = (int) $successRate;
        }
        
        $tier = 'Poor';
        if ($score >= 900) $tier = 'Excellent';
        elseif ($score >= 750) $tier = 'Good';
        elseif ($score >= 500) $tier = 'Average';
        
        $farmer->update([
            'blockchain_trust_score' => $score,
            'trust_tier' => $tier,
        ]);
    }
}
