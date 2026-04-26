<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SmartSellingAdvisorController extends Controller
{
    // Market data with prices and demand for different locations
    private $marketDatabase = [
        'maize' => [
            'name' => 'Maize',
            'icon' => '🌽',
            'unit' => 'per 90kg bag',
            'markets' => [
                'arusha' => ['price' => 65000, 'demand' => 'High', 'buyers' => 45, 'distance' => 0],
                'dodoma' => ['price' => 62000, 'demand' => 'Medium', 'buyers' => 32, 'distance' => 320],
                'dar_es_salaam' => ['price' => 71000, 'demand' => 'High', 'buyers' => 78, 'distance' => 650],
                'mwanza' => ['price' => 64000, 'demand' => 'Medium', 'buyers' => 38, 'distance' => 520],
                'mbeya' => ['price' => 63000, 'demand' => 'Medium', 'buyers' => 28, 'distance' => 480],
                'morogoro' => ['price' => 68000, 'demand' => 'High', 'buyers' => 52, 'distance' => 450],
                'kilimanjaro' => ['price' => 66000, 'demand' => 'High', 'buyers' => 42, 'distance' => 180],
                'tanga' => ['price' => 67000, 'demand' => 'Medium', 'buyers' => 35, 'distance' => 380],
            ],
        ],
        'coffee' => [
            'name' => 'Coffee (Arabica)',
            'icon' => '☕',
            'unit' => 'per kg',
            'markets' => [
                'arusha' => ['price' => 8500, 'demand' => 'High', 'buyers' => 25, 'distance' => 0],
                'dodoma' => ['price' => 8200, 'demand' => 'Low', 'buyers' => 8, 'distance' => 320],
                'dar_es_salaam' => ['price' => 9200, 'demand' => 'High', 'buyers' => 42, 'distance' => 650],
                'mwanza' => ['price' => 8000, 'demand' => 'Low', 'buyers' => 6, 'distance' => 520],
                'mbeya' => ['price' => 8800, 'demand' => 'High', 'buyers' => 35, 'distance' => 480],
                'morogoro' => ['price' => 8900, 'demand' => 'Medium', 'buyers' => 22, 'distance' => 450],
                'kilimanjaro' => ['price' => 9100, 'demand' => 'High', 'buyers' => 38, 'distance' => 180],
                'tanga' => ['price' => 8600, 'demand' => 'Medium', 'buyers' => 18, 'distance' => 380],
            ],
        ],
        'rice' => [
            'name' => 'Rice',
            'icon' => '🍚',
            'unit' => 'per 90kg bag',
            'markets' => [
                'arusha' => ['price' => 85000, 'demand' => 'Medium', 'buyers' => 28, 'distance' => 0],
                'dodoma' => ['price' => 82000, 'demand' => 'Medium', 'buyers' => 24, 'distance' => 320],
                'dar_es_salaam' => ['price' => 95000, 'demand' => 'High', 'buyers' => 65, 'distance' => 650],
                'mwanza' => ['price' => 80000, 'demand' => 'High', 'buyers' => 45, 'distance' => 520],
                'mbeya' => ['price' => 78000, 'demand' => 'Low', 'buyers' => 12, 'distance' => 480],
                'morogoro' => ['price' => 88000, 'demand' => 'High', 'buyers' => 38, 'distance' => 450],
                'kilimanjaro' => ['price' => 86000, 'demand' => 'Medium', 'buyers' => 22, 'distance' => 180],
                'tanga' => ['price' => 83000, 'demand' => 'Medium', 'buyers' => 20, 'distance' => 380],
            ],
        ],
        'beans' => [
            'name' => 'Beans',
            'icon' => '🫘',
            'unit' => 'per 90kg bag',
            'markets' => [
                'arusha' => ['price' => 185000, 'demand' => 'High', 'buyers' => 35, 'distance' => 0],
                'dodoma' => ['price' => 178000, 'demand' => 'Medium', 'buyers' => 28, 'distance' => 320],
                'dar_es_salaam' => ['price' => 210000, 'demand' => 'High', 'buyers' => 58, 'distance' => 650],
                'mwanza' => ['price' => 172000, 'demand' => 'Medium', 'buyers' => 25, 'distance' => 520],
                'mbeya' => ['price' => 168000, 'demand' => 'Low', 'buyers' => 15, 'distance' => 480],
                'morogoro' => ['price' => 195000, 'demand' => 'High', 'buyers' => 42, 'distance' => 450],
                'kilimanjaro' => ['price' => 190000, 'demand' => 'High', 'buyers' => 32, 'distance' => 180],
                'tanga' => ['price' => 182000, 'demand' => 'Medium', 'buyers' => 24, 'distance' => 380],
            ],
        ],
        'cashew' => [
            'name' => 'Cashew Nuts',
            'icon' => '🥜',
            'unit' => 'per kg',
            'markets' => [
                'arusha' => ['price' => 3800, 'demand' => 'Medium', 'buyers' => 18, 'distance' => 0],
                'dodoma' => ['price' => 3500, 'demand' => 'Low', 'buyers' => 8, 'distance' => 320],
                'dar_es_salaam' => ['price' => 4500, 'demand' => 'High', 'buyers' => 52, 'distance' => 650],
                'mwanza' => ['price' => 3600, 'demand' => 'Low', 'buyers' => 10, 'distance' => 520],
                'mbeya' => ['price' => 3400, 'demand' => 'Low', 'buyers' => 6, 'distance' => 480],
                'morogoro' => ['price' => 4200, 'demand' => 'Medium', 'buyers' => 28, 'distance' => 450],
                'kilimanjaro' => ['price' => 4000, 'demand' => 'Medium', 'buyers' => 22, 'distance' => 180],
                'tanga' => ['price' => 4600, 'demand' => 'High', 'buyers' => 48, 'distance' => 380],
            ],
        ],
        'tea' => [
            'name' => 'Tea',
            'icon' => '🍃',
            'unit' => 'per kg green leaf',
            'markets' => [
                'arusha' => ['price' => 380, 'demand' => 'Medium', 'buyers' => 12, 'distance' => 0],
                'dodoma' => ['price' => 320, 'demand' => 'Low', 'buyers' => 4, 'distance' => 320],
                'dar_es_salaam' => ['price' => 420, 'demand' => 'High', 'buyers' => 35, 'distance' => 650],
                'mwanza' => ['price' => 340, 'demand' => 'Low', 'buyers' => 5, 'distance' => 520],
                'mbeya' => ['price' => 400, 'demand' => 'High', 'buyers' => 28, 'distance' => 480],
                'morogoro' => ['price' => 410, 'demand' => 'Medium', 'buyers' => 18, 'distance' => 450],
                'kilimanjaro' => ['price' => 390, 'demand' => 'High', 'buyers' => 32, 'distance' => 180],
                'tanga' => ['price' => 360, 'demand' => 'Medium', 'buyers' => 15, 'distance' => 380],
            ],
        ],
    ];

    private $locationNames = [
        'arusha' => 'Arusha',
        'dodoma' => 'Dodoma',
        'dar_es_salaam' => 'Dar es Salaam',
        'mwanza' => 'Mwanza',
        'mbeya' => 'Mbeya',
        'morogoro' => 'Morogoro',
        'kilimanjaro' => 'Kilimanjaro',
        'tanga' => 'Tanga',
    ];

    public function index()
    {
        $crops = collect($this->marketDatabase)->map(function($crop, $key) {
            return ['key' => $key, 'name' => $crop['name'], 'icon' => $crop['icon']];
        })->values();

        $locations = collect($this->locationNames)->map(function($name, $key) {
            return ['key' => $key, 'name' => $name];
        })->values();

        $recommendation = session('recommendation');

        return view('farmer.selling-advisor', compact('crops', 'locations', 'recommendation'));
    }

    public function analyze(Request $request)
    {
        $validated = $request->validate([
            'crop' => 'required|in:' . implode(',', array_keys($this->marketDatabase)),
            'location' => 'required|in:' . implode(',', array_keys($this->locationNames)),
        ]);

        $crop = $this->marketDatabase[$validated['crop']];
        $farmerLocation = $validated['location'];
        $allMarkets = $crop['markets'];

        // Calculate scores for each market
        $scoredMarkets = [];
        foreach ($allMarkets as $marketKey => $market) {
            $score = 0;
            
            // Price score (40% weight) - higher is better
            $maxPrice = max(array_column($allMarkets, 'price'));
            $priceScore = ($market['price'] / $maxPrice) * 40;
            
            // Demand score (30% weight) - High=30, Medium=20, Low=10
            $demandScores = ['High' => 30, 'Medium' => 20, 'Low' => 10];
            $demandScore = $demandScores[$market['demand']] ?? 10;
            
            // Distance score (20% weight) - closer is better
            $maxDistance = max(array_column($allMarkets, 'distance'));
            $distanceScore = $maxDistance > 0 
                ? ((1 - ($market['distance'] / $maxDistance)) * 20) 
                : 20;
            
            // Buyer availability (10% weight)
            $maxBuyers = max(array_column($allMarkets, 'buyers'));
            $buyerScore = ($market['buyers'] / $maxBuyers) * 10;
            
            $totalScore = $priceScore + $demandScore + $distanceScore + $buyerScore;
            
            $scoredMarkets[$marketKey] = [
                'key' => $marketKey,
                'name' => $this->locationNames[$marketKey],
                'price' => $market['price'],
                'demand' => $market['demand'],
                'buyers' => $market['buyers'],
                'distance' => $market['distance'],
                'score' => round($totalScore, 1),
                'is_farmer_location' => $marketKey === $farmerLocation,
            ];
        }

        // Sort by score descending
        uasort($scoredMarkets, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $rankedMarkets = array_values($scoredMarkets);
        $bestMarket = $rankedMarkets[0];
        
        // Calculate potential earnings vs local market
        $localMarket = $allMarkets[$farmerLocation];
        $potentialGain = $bestMarket['price'] - $localMarket['price'];
        $gainPercent = $localMarket['price'] > 0 
            ? round(($potentialGain / $localMarket['price']) * 100, 1) 
            : 0;

        // Transport cost estimate (rough: 500 TZS per km per ton)
        $transportCost = $bestMarket['distance'] * 500;

        $recommendation = [
            'crop_name' => $crop['name'],
            'crop_icon' => $crop['icon'],
            'unit' => $crop['unit'],
            'farmer_location' => $this->locationNames[$farmerLocation],
            'best_market' => $bestMarket,
            'all_markets' => $rankedMarkets,
            'potential_gain' => $potentialGain,
            'gain_percent' => $gainPercent,
            'transport_cost' => $transportCost,
            'net_benefit' => $potentialGain - $transportCost,
            'analysis_date' => now()->format('F j, Y'),
        ];

        return redirect()->route('farmer.selling-advisor')
            ->with('recommendation', $recommendation);
    }
}
