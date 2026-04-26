<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class GlobalMarketController extends Controller
{
    // Market data with dummy prices in USD per ton
    private $marketData = [
        'maize' => [
            'name' => 'Maize',
            'icon' => '🌽',
            'markets' => [
                'tanzania' => [
                    'price' => 320,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+5%',
                    'trend_up' => true,
                ],
                'africa' => [
                    'price' => 290,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+2%',
                    'trend_up' => true,
                ],
                'global' => [
                    'price' => 245,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '-1%',
                    'trend_up' => false,
                ],
            ],
            'best_market' => 'tanzania',
            'reason' => 'Local demand is strong due to food security initiatives',
        ],
        'coffee' => [
            'name' => 'Coffee (Arabica)',
            'icon' => '☕',
            'markets' => [
                'tanzania' => [
                    'price' => 4200,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+8%',
                    'trend_up' => true,
                ],
                'africa' => [
                    'price' => 4100,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+12%',
                    'trend_up' => true,
                ],
                'global' => [
                    'price' => 4650,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+15%',
                    'trend_up' => true,
                ],
            ],
            'best_market' => 'global',
            'reason' => 'International specialty coffee demand is surging',
        ],
        'rice' => [
            'name' => 'Rice',
            'icon' => '🍚',
            'markets' => [
                'tanzania' => [
                    'price' => 480,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+3%',
                    'trend_up' => true,
                ],
                'africa' => [
                    'price' => 450,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+4%',
                    'trend_up' => true,
                ],
                'global' => [
                    'price' => 425,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+1%',
                    'trend_up' => true,
                ],
            ],
            'best_market' => 'africa',
            'reason' => 'Regional demand exceeds local production capacity',
        ],
        'cashew' => [
            'name' => 'Cashew Nuts',
            'icon' => '🥜',
            'markets' => [
                'tanzania' => [
                    'price' => 1250,
                    'demand' => 'Low',
                    'demand_color' => 'red',
                    'trend' => '-5%',
                    'trend_up' => false,
                ],
                'africa' => [
                    'price' => 1380,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+2%',
                    'trend_up' => true,
                ],
                'global' => [
                    'price' => 1650,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+10%',
                    'trend_up' => true,
                ],
            ],
            'best_market' => 'global',
            'reason' => 'Export markets offer 32% premium over local prices',
        ],
        'tea' => [
            'name' => 'Tea',
            'icon' => '🍃',
            'markets' => [
                'tanzania' => [
                    'price' => 1850,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+3%',
                    'trend_up' => true,
                ],
                'africa' => [
                    'price' => 1950,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+5%',
                    'trend_up' => true,
                ],
                'global' => [
                    'price' => 2100,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+7%',
                    'trend_up' => true,
                ],
            ],
            'best_market' => 'global',
            'reason' => 'Mombasa auction and direct exports fetch premium',
        ],
        'beans' => [
            'name' => 'Beans',
            'icon' => '🫘',
            'markets' => [
                'tanzania' => [
                    'price' => 850,
                    'demand' => 'High',
                    'demand_color' => 'green',
                    'trend' => '+6%',
                    'trend_up' => true,
                ],
                'africa' => [
                    'price' => 780,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+3%',
                    'trend_up' => true,
                ],
                'global' => [
                    'price' => 720,
                    'demand' => 'Medium',
                    'demand_color' => 'yellow',
                    'trend' => '+2%',
                    'trend_up' => true,
                ],
            ],
            'best_market' => 'tanzania',
            'reason' => 'Strong domestic demand for protein source',
        ],
    ];

    private $marketInsights = [
        'trending_up' => ['coffee', 'tea', 'cashew'],
        'trending_down' => ['cashew_local'],
        'high_demand' => ['maize', 'rice', 'beans', 'coffee', 'tea'],
        'best_export' => 'cashew',
        'best_local' => 'maize',
    ];

    public function index()
    {
        $crops = $this->marketData;
        $insights = $this->generateInsights();
        
        return view('market.global-intelligence', compact('crops', 'insights'));
    }

    private function generateInsights()
    {
        $insights = [];
        
        // Find highest price opportunity
        $maxPriceDiff = 0;
        $bestOpportunity = null;
        
        foreach ($this->marketData as $cropKey => $crop) {
            $tanzaniaPrice = $crop['markets']['tanzania']['price'];
            $globalPrice = $crop['markets']['global']['price'];
            $diff = $globalPrice - $tanzaniaPrice;
            $percentDiff = ($diff / $tanzaniaPrice) * 100;
            
            if ($percentDiff > $maxPriceDiff) {
                $maxPriceDiff = $percentDiff;
                $bestOpportunity = [
                    'crop' => $crop['name'],
                    'crop_key' => $cropKey,
                    'icon' => $crop['icon'],
                    'local_price' => $tanzaniaPrice,
                    'export_price' => $globalPrice,
                    'difference' => $diff,
                    'percent_gain' => round($percentDiff, 1),
                ];
            }
        }
        
        return [
            'best_opportunity' => $bestOpportunity,
            'trending_count' => count($this->marketInsights['trending_up']),
            'high_demand_count' => count($this->marketInsights['high_demand']),
            'last_updated' => now()->format('M j, Y H:i'),
        ];
    }
}
