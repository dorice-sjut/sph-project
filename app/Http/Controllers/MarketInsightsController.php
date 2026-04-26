<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MarketInsightsController extends Controller
{
    public function index(Request $request)
    {
        // Available crops
        $crops = [
            ['name' => 'Maize', 'icon' => 'grain'],
            ['name' => 'Rice', 'icon' => 'rice'],
            ['name' => 'Wheat', 'icon' => 'grass'],
            ['name' => 'Beans', 'icon' => 'eco'],
            ['name' => 'Coffee', 'icon' => 'coffee'],
            ['name' => 'Tea', 'icon' => 'emoji_food_beverage'],
        ];

        // Get selected crop or default to Maize
        $selectedCrop = $request->get('crop', 'Maize');
        
        // Get farmer location or default to Arusha
        $farmerLocations = [
            'Arusha' => ['region' => 'Northern', 'zone' => 'Highlands'],
            'Dodoma' => ['region' => 'Central', 'zone' => 'Semi-arid'],
            'Mwanza' => ['region' => 'Lake', 'zone' => 'Lake Zone'],
            'Dar es Salaam' => ['region' => 'Coastal', 'zone' => 'Coast'],
            'Mbeya' => ['region' => 'Southern', 'zone' => 'Highlands'],
            'Morogoro' => ['region' => 'Eastern', 'zone' => 'Semi-arid'],
        ];
        $selectedLocation = $request->get('location', 'Arusha');
        $farmerZone = $farmerLocations[$selectedLocation]['zone'] ?? 'Highlands';

        // Price data for charts by crop (dummy data)
        $cropPriceData = [
            'Maize' => [
                'tanzania' => [120, 125, 118, 130, 135, 128, 140, 145, 138, 150, 155, 148],
                'africa' => [110, 115, 112, 118, 122, 120, 128, 132, 125, 138, 142, 135],
                'global' => [100, 105, 108, 112, 115, 118, 120, 125, 122, 130, 135, 128],
            ],
            'Rice' => [
                'tanzania' => [180, 185, 178, 190, 195, 188, 200, 205, 198, 210, 215, 208],
                'africa' => [95, 98, 92, 100, 105, 102, 108, 112, 108, 115, 120, 118],
                'global' => [220, 225, 218, 230, 235, 228, 240, 245, 238, 250, 255, 248],
            ],
            'Coffee' => [
                'tanzania' => [300, 310, 295, 320, 335, 325, 340, 350, 340, 360, 375, 365],
                'africa' => [280, 285, 275, 290, 300, 295, 310, 320, 315, 330, 340, 335],
                'global' => [350, 360, 345, 370, 385, 375, 390, 400, 390, 410, 425, 415],
            ],
            'Beans' => [
                'tanzania' => [160, 165, 158, 170, 175, 168, 180, 185, 178, 190, 195, 188],
                'africa' => [140, 145, 138, 150, 155, 148, 160, 165, 158, 170, 175, 168],
                'global' => [180, 185, 178, 190, 195, 188, 200, 205, 198, 210, 215, 208],
            ],
            'Wheat' => [
                'tanzania' => [200, 205, 198, 210, 215, 208, 220, 225, 218, 230, 235, 228],
                'africa' => [180, 185, 178, 190, 195, 188, 200, 205, 198, 210, 215, 208],
                'global' => [210, 215, 208, 220, 225, 218, 230, 235, 228, 240, 245, 238],
            ],
            'Tea' => [
                'tanzania' => [250, 255, 248, 260, 265, 258, 270, 275, 268, 280, 285, 278],
                'africa' => [260, 265, 258, 270, 275, 268, 280, 285, 278, 290, 295, 288],
                'global' => [280, 285, 278, 290, 295, 288, 300, 305, 298, 310, 315, 308],
            ],
        ];

        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        
        // Get price trends for selected crop
        $priceTrends = [
            'labels' => $labels,
            'tanzania' => $cropPriceData[$selectedCrop]['tanzania'] ?? $cropPriceData['Maize']['tanzania'],
            'africa' => $cropPriceData[$selectedCrop]['africa'] ?? $cropPriceData['Maize']['africa'],
            'global' => $cropPriceData[$selectedCrop]['global'] ?? $cropPriceData['Maize']['global'],
        ];

        // Regional market data
        $markets = [
            [
                'region' => 'Tanzania',
                'country' => 'Arusha',
                'flag' => '🇹🇿',
                'crops' => [
                    ['name' => 'Maize', 'price' => 145, 'unit' => 'kg', 'trend' => '+12%', 'trend_up' => true],
                    ['name' => 'Beans', 'price' => 180, 'unit' => 'kg', 'trend' => '+8%', 'trend_up' => true],
                    ['name' => 'Coffee', 'price' => 320, 'unit' => 'kg', 'trend' => '+15%', 'trend_up' => true],
                ],
                'demand' => 'High',
                'score' => 92,
            ],
            [
                'region' => 'Africa',
                'country' => 'Nairobi, Kenya',
                'flag' => '🇰🇪',
                'crops' => [
                    ['name' => 'Maize', 'price' => 132, 'unit' => 'kg', 'trend' => '+5%', 'trend_up' => true],
                    ['name' => 'Rice', 'price' => 95, 'unit' => 'kg', 'trend' => '-2%', 'trend_up' => false],
                    ['name' => 'Tea', 'price' => 280, 'unit' => 'kg', 'trend' => '+7%', 'trend_up' => true],
                ],
                'demand' => 'Medium',
                'score' => 78,
            ],
            [
                'region' => 'Global',
                'country' => 'Chicago, USA',
                'flag' => '🇺🇸',
                'crops' => [
                    ['name' => 'Maize', 'price' => 125, 'unit' => 'kg', 'trend' => '+3%', 'trend_up' => true],
                    ['name' => 'Wheat', 'price' => 210, 'unit' => 'kg', 'trend' => '-5%', 'trend_up' => false],
                    ['name' => 'Soybeans', 'price' => 380, 'unit' => 'kg', 'trend' => '+2%', 'trend_up' => true],
                ],
                'demand' => 'High',
                'score' => 85,
            ],
            [
                'region' => 'Africa',
                'country' => 'Lagos, Nigeria',
                'flag' => '🇳🇬',
                'crops' => [
                    ['name' => 'Rice', 'price' => 110, 'unit' => 'kg', 'trend' => '+18%', 'trend_up' => true],
                    ['name' => 'Cassava', 'price' => 65, 'unit' => 'kg', 'trend' => '+10%', 'trend_up' => true],
                    ['name' => 'Yams', 'price' => 140, 'unit' => 'kg', 'trend' => '+22%', 'trend_up' => true],
                ],
                'demand' => 'Very High',
                'score' => 96,
            ],
        ];

        // Calculate best market based on farmer location and crop (simplified logic)
        $marketScores = [];
        foreach ($markets as $market) {
            $score = $market['score'];
            
            // Adjust score based on farmer location
            if ($market['region'] === 'Tanzania') {
                $score += 15; // Favor local markets for Tanzanian farmers
            } elseif ($market['region'] === 'Africa') {
                $score += 5; // Slight preference for African markets
            }
            
            // Adjust based on crop match
            foreach ($market['crops'] as $crop) {
                if ($crop['name'] === $selectedCrop) {
                    $score += 20; // Boost if market trades the selected crop
                    if ($crop['trend_up']) {
                        $score += 10; // Extra boost if price is trending up
                    }
                }
            }
            
            $marketScores[] = array_merge($market, ['calculated_score' => $score]);
        }
        
        // Sort by calculated score
        usort($marketScores, function($a, $b) {
            return $b['calculated_score'] - $a['calculated_score'];
        });
        
        $bestMarket = $marketScores[0];
        
        // Get top 3 recommended markets
        $recommendedMarkets = array_slice($marketScores, 0, 3);

        return view('market-insights.index', compact(
            'crops',
            'selectedCrop',
            'farmerLocations',
            'selectedLocation',
            'farmerZone',
            'priceTrends',
            'markets',
            'bestMarket',
            'recommendedMarkets'
        ));
    }
}
