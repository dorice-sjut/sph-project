<?php

namespace Database\Seeders;

use App\Models\MarketPrice;
use Illuminate\Database\Seeder;

class MarketPriceSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'Maize', 'category' => 'Grains'],
            ['name' => 'Rice', 'category' => 'Grains'],
            ['name' => 'Wheat', 'category' => 'Grains'],
            ['name' => 'Tomatoes', 'category' => 'Vegetables'],
            ['name' => 'Onions', 'category' => 'Vegetables'],
            ['name' => 'Potatoes', 'category' => 'Vegetables'],
            ['name' => 'Carrots', 'category' => 'Vegetables'],
            ['name' => 'Cabbage', 'category' => 'Vegetables'],
            ['name' => 'Bananas', 'category' => 'Fruits'],
            ['name' => 'Mangoes', 'category' => 'Fruits'],
            ['name' => 'Avocados', 'category' => 'Fruits'],
            ['name' => 'Pineapples', 'category' => 'Fruits'],
            ['name' => 'Beans', 'category' => 'Legumes'],
            ['name' => 'Pigeon Peas', 'category' => 'Legumes'],
            ['name' => 'Groundnuts', 'category' => 'Legumes'],
            ['name' => 'Milk', 'category' => 'Dairy'],
            ['name' => 'Eggs', 'category' => 'Dairy'],
            ['name' => 'Chicken', 'category' => 'Meat'],
            ['name' => 'Beef', 'category' => 'Meat'],
            ['name' => 'Goat Meat', 'category' => 'Meat'],
        ];

        $markets = [
            ['name' => 'Dar es Salaam Central Market', 'region' => 'Dar es Salaam', 'country' => 'Tanzania'],
            ['name' => 'Arusha Central Market', 'region' => 'Arusha', 'country' => 'Tanzania'],
            ['name' => 'Mwanza Central Market', 'region' => 'Mwanza', 'country' => 'Tanzania'],
            ['name' => 'Dodoma Central Market', 'region' => 'Dodoma', 'country' => 'Tanzania'],
            ['name' => 'Moshi Central Market', 'region' => 'Kilimanjaro', 'country' => 'Tanzania'],
            ['name' => 'Nairobi City Market', 'region' => 'Nairobi', 'country' => 'Kenya'],
            ['name' => 'Kampala Central Market', 'region' => 'Kampala', 'country' => 'Uganda'],
            ['name' => 'Kigali Market', 'region' => 'Kigali', 'country' => 'Rwanda'],
        ];

        foreach ($products as $product) {
            foreach ($markets as $market) {
                $basePrice = match($product['category']) {
                    'Grains' => rand(20, 50),
                    'Vegetables' => rand(10, 30),
                    'Fruits' => rand(15, 60),
                    'Legumes' => rand(40, 80),
                    'Dairy' => rand(15, 35),
                    'Meat' => rand(80, 150),
                    default => rand(20, 100),
                };

                // Add some variation based on country
                $priceMultiplier = match($market['country']) {
                    'Kenya' => 1.2,
                    'Uganda' => 0.9,
                    'Rwanda' => 1.1,
                    default => 1.0,
                };

                $priceUsd = round($basePrice * $priceMultiplier, 2);
                $change = round(rand(-50, 50) / 10, 1);
                $trend = $change > 2 ? 'up' : ($change < -2 ? 'down' : 'stable');

                MarketPrice::create([
                    'product_name' => $product['name'],
                    'category' => $product['category'],
                    'price_local' => $priceUsd * 2500, // Approximate TZS conversion
                    'currency_local' => 'TZS',
                    'price_usd' => $priceUsd,
                    'region' => $market['region'],
                    'country' => $market['country'],
                    'market_name' => $market['name'],
                    'price_change_24h' => $change,
                    'trend' => $trend,
                    'price_date' => now()->subDays(rand(0, 5)),
                ]);
            }
        }
    }
}
