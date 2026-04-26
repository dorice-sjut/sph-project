<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name', 'category', 'price_local', 'currency_local', 'price_usd',
        'region', 'country', 'market_name', 'price_change_24h', 'trend', 'price_date', 'metadata'
    ];

    protected $casts = [
        'price_local' => 'decimal:2',
        'price_usd' => 'decimal:2',
        'price_change_24h' => 'decimal:2',
        'price_date' => 'date',
        'metadata' => 'array',
    ];

    public function scopeByCountry($query, $country)
    {
        return $query->where('country', $country);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeLatestPrices($query)
    {
        return $query->orderBy('price_date', 'desc');
    }

    public function getTrendIconAttribute()
    {
        return match($this->trend) {
            'up' => 'trending_up',
            'down' => 'trending_down',
            default => 'trending_flat'
        };
    }

    public function getTrendColorAttribute()
    {
        return match($this->trend) {
            'up' => 'text-green-500',
            'down' => 'text-red-500',
            default => 'text-gray-500'
        };
    }
}
