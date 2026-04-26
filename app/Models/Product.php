<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id', 'name', 'description', 'category', 'price', 'price_unit',
        'quantity', 'quantity_unit', 'location', 'latitude', 'longitude',
        'images', 'status', 'is_organic', 'harvest_date', 'expiry_date',
        // Blockchain fields
        'is_blockchain_verified', 'batch_id', 'blockchain_tx_hash',
        'verification_contract_address', 'blockchain_verified_at', 'ipfs_hash'
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'quantity' => 'decimal:2',
        'is_organic' => 'boolean',
        'harvest_date' => 'date',
        'expiry_date' => 'date',
        'is_blockchain_verified' => 'boolean',
        'blockchain_verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function getFirstImageAttribute()
    {
        $images = $this->images ?? [];
        return $images[0] ?? null;
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeNearby($query, $lat, $lng, $radius = 50)
    {
        return $query->selectRaw(
            "*, (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance",
            [$lat, $lng, $lat]
        )->having('distance', '<=', $radius)->orderBy('distance');
    }

    public function scopeBlockchainVerified($query)
    {
        return $query->where('is_blockchain_verified', true);
    }

    public function blockchainTransactions()
    {
        return $this->morphMany(BlockchainTransaction::class, 'related');
    }

    public function getVerificationBadgeAttribute(): string
    {
        if ($this->is_blockchain_verified) {
            return 'verified';
        }
        return 'unverified';
    }

    public function getBlockchainExplorerUrlAttribute(): ?string
    {
        if (!$this->blockchain_tx_hash) {
            return null;
        }
        
        $network = config('blockchain.network', 'sepolia');
        $baseUrl = $network === 'mainnet' 
            ? 'https://etherscan.io' 
            : "https://{$network}.etherscan.io";
        
        return "{$baseUrl}/tx/{$this->blockchain_tx_hash}";
    }
}
