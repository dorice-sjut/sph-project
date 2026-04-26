<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BudgetController extends Controller
{
    public function index(Request $request)
    {
        // Crop data with typical costs and yields
        $cropData = [
            'Maize' => [
                'seed_cost_per_acre' => 50,
                'fertilizer_cost_per_acre' => 120,
                'labor_cost_per_acre' => 80,
                'pesticide_cost_per_acre' => 30,
                'expected_yield_per_acre' => 25, // bags
                'market_price_per_unit' => 25, // per bag
                'growing_months' => 4,
            ],
            'Rice' => [
                'seed_cost_per_acre' => 40,
                'fertilizer_cost_per_acre' => 150,
                'labor_cost_per_acre' => 100,
                'pesticide_cost_per_acre' => 40,
                'expected_yield_per_acre' => 30, // bags
                'market_price_per_unit' => 35, // per bag
                'growing_months' => 5,
            ],
            'Coffee' => [
                'seed_cost_per_acre' => 100,
                'fertilizer_cost_per_acre' => 200,
                'labor_cost_per_acre' => 150,
                'pesticide_cost_per_acre' => 80,
                'expected_yield_per_acre' => 15, // bags
                'market_price_per_unit' => 120, // per bag
                'growing_months' => 12,
            ],
            'Beans' => [
                'seed_cost_per_acre' => 60,
                'fertilizer_cost_per_acre' => 100,
                'labor_cost_per_acre' => 90,
                'pesticide_cost_per_acre' => 35,
                'expected_yield_per_acre' => 18, // bags
                'market_price_per_unit' => 45, // per bag
                'growing_months' => 3,
            ],
            'Wheat' => [
                'seed_cost_per_acre' => 55,
                'fertilizer_cost_per_acre' => 130,
                'labor_cost_per_acre' => 85,
                'pesticide_cost_per_acre' => 45,
                'expected_yield_per_acre' => 22, // bags
                'market_price_per_unit' => 30, // per bag
                'growing_months' => 4,
            ],
            'Tea' => [
                'seed_cost_per_acre' => 120,
                'fertilizer_cost_per_acre' => 180,
                'labor_cost_per_acre' => 200,
                'pesticide_cost_per_acre' => 60,
                'expected_yield_per_acre' => 40, // bags
                'market_price_per_unit' => 28, // per bag
                'growing_months' => 12,
            ],
        ];

        $crops = array_keys($cropData);
        
        // Get user inputs or use defaults
        $selectedCrop = $request->get('crop', 'Maize');
        $acres = (int) $request->get('acres', 1);
        $budget = (float) $request->get('budget', 0);
        
        // Calculate budget breakdown
        $crop = $cropData[$selectedCrop];
        $totalSeedCost = $crop['seed_cost_per_acre'] * $acres;
        $totalFertilizerCost = $crop['fertilizer_cost_per_acre'] * $acres;
        $totalLaborCost = $crop['labor_cost_per_acre'] * $acres;
        $totalPesticideCost = $crop['pesticide_cost_per_acre'] * $acres;
        $totalInvestment = $totalSeedCost + $totalFertilizerCost + $totalLaborCost + $totalPesticideCost;
        
        // Calculate expected returns
        $totalYield = $crop['expected_yield_per_acre'] * $acres;
        $expectedRevenue = $totalYield * $crop['market_price_per_unit'];
        $netProfit = $expectedRevenue - $totalInvestment;
        $roi = $totalInvestment > 0 ? round(($netProfit / $totalInvestment) * 100, 1) : 0;
        
        // AI Recommendations
        $recommendations = [];
        
        // Budget vs Investment analysis
        if ($budget > 0) {
            if ($budget >= $totalInvestment) {
                $recommendations[] = [
                    'type' => 'success',
                    'icon' => 'check_circle',
                    'title' => 'Budget Sufficient',
                    'message' => "Your budget of \${$budget} covers the estimated investment of \${$totalInvestment}. You have \$" . ($budget - $totalInvestment) . " buffer for unexpected costs.",
                ];
            } else {
                $shortfall = $totalInvestment - $budget;
                $recommendations[] = [
                    'type' => 'warning',
                    'icon' => 'warning',
                    'title' => 'Budget Shortfall',
                    'message' => "Your budget of \${$budget} is \${$shortfall} short. Consider reducing acreage to " . floor($budget / ($totalInvestment / $acres)) . " acres or seek additional funding.",
                ];
            }
        }
        
        // ROI analysis
        if ($roi >= 50) {
            $recommendations[] = [
                'type' => 'success',
                'icon' => 'trending_up',
                'title' => 'Excellent ROI',
                'message' => "Expected ROI of {$roi}% is very good. This crop is highly profitable for your investment.",
            ];
        } elseif ($roi >= 20) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => 'trending_flat',
                'title' => 'Good ROI',
                'message' => "Expected ROI of {$roi}% is reasonable. Consider optimizing costs to improve margins.",
            ];
        } elseif ($roi > 0) {
            $recommendations[] = [
                'type' => 'warning',
                'icon' => 'info',
                'title' => 'Low ROI',
                'message' => "Expected ROI of {$roi}% is low. Look for premium markets or reduce input costs.",
            ];
        } else {
            $recommendations[] = [
                'type' => 'error',
                'icon' => 'trending_down',
                'title' => 'Negative ROI',
                'message' => "This crop is projected to lose money. Consider different crops or improve yields.",
            ];
        }
        
        // Crop-specific advice
        if ($crop['growing_months'] > 6) {
            $recommendations[] = [
                'type' => 'info',
                'icon' => 'schedule',
                'title' => 'Long Growing Cycle',
                'message' => "This crop takes {$crop['growing_months']} months to mature. Ensure you have working capital for the entire period.",
            ];
        }
        
        // Risk assessment
        $riskLevel = 'Medium';
        if ($roi > 40 && $crop['growing_months'] <= 4) {
            $riskLevel = 'Low';
        } elseif ($roi < 10 || $crop['growing_months'] > 10) {
            $riskLevel = 'High';
        }
        
        return view('farmer.budget', compact(
            'crops',
            'selectedCrop',
            'acres',
            'budget',
            'crop',
            'totalSeedCost',
            'totalFertilizerCost',
            'totalLaborCost',
            'totalPesticideCost',
            'totalInvestment',
            'totalYield',
            'expectedRevenue',
            'netProfit',
            'roi',
            'recommendations',
            'riskLevel'
        ));
    }
}
