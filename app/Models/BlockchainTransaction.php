<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlockchainTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'tx_hash',
        'contract_name',
        'contract_address',
        'function_name',
        'parameters',
        'status',
        'block_number',
        'gas_used',
        'gas_price',
        'from_address',
        'to_address',
        'value',
        'user_id',
        'related_type',
        'related_id',
        'error_message',
        'confirmed_at',
        'confirmations'
    ];

    protected $casts = [
        'parameters' => 'array',
        'value' => 'decimal:18',
        'gas_price' => 'decimal:10',
        'confirmed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function related()
    {
        return $this->morphTo();
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeByContract($query, $contract)
    {
        return $query->where('contract_name', $contract);
    }

    public function isConfirmed(): bool
    {
        return $this->status === 'confirmed' && $this->confirmations >= 12;
    }

    public function getEtherscanUrlAttribute(): ?string
    {
        $network = config('blockchain.network', 'sepolia');
        $baseUrl = $network === 'mainnet' 
            ? 'https://etherscan.io' 
            : "https://{$network}.etherscan.io";
        
        return $this->tx_hash ? "{$baseUrl}/tx/{$this->tx_hash}" : null;
    }
}
