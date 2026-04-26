<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'buyer_id', 'seller_id', 'product_id', 'quantity', 'total_price',
        'status', 'delivery_address', 'delivery_lat', 'delivery_lng',
        'payment_status', 'payment_method', 'notes', 'ordered_at', 'delivered_at',
        // Blockchain escrow fields
        'is_escrow_payment', 'escrow_contract_address', 'escrow_tx_hash', 'escrow_status',
        'escrow_created_at', 'escrow_delivered_at', 'escrow_released_at',
        'escrow_amount_eth', 'platform_fee_eth', 'buyer_wallet_address', 'farmer_wallet_address'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'total_price' => 'decimal:2',
        'ordered_at' => 'datetime',
        'delivered_at' => 'datetime',
        'is_escrow_payment' => 'boolean',
        'escrow_created_at' => 'datetime',
        'escrow_delivered_at' => 'datetime',
        'escrow_released_at' => 'datetime',
        'escrow_amount_eth' => 'decimal:18',
        'platform_fee_eth' => 'decimal:18',
    ];

    public function buyer()
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller()
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->where('buyer_id', $userId)->orWhere('seller_id', $userId);
        });
    }

    public function scopeEscrow($query)
    {
        return $query->where('is_escrow_payment', true);
    }

    public function blockchainTransactions()
    {
        return $this->morphMany(BlockchainTransaction::class, 'related');
    }

    public function getEtherscanUrlAttribute(): ?string
    {
        if (!$this->escrow_tx_hash) {
            return null;
        }
        
        $network = config('blockchain.network', 'sepolia');
        $baseUrl = $network === 'mainnet' 
            ? 'https://etherscan.io' 
            : "https://{$network}.etherscan.io";
        
        return "{$baseUrl}/tx/{$this->escrow_tx_hash}";
    }
}
