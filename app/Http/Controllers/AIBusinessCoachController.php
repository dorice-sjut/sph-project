<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AIBusinessCoachController extends Controller
{
    private $cropData = [
        'maize' => [
            'name' => 'Maize',
            'seed_cost' => 45000,
            'fertilizer_cost' => 120000,
            'pesticide_cost' => 25000,
            'labor_cost' => 80000,
            'yield_per_acre' => 25,
            'price_per_unit' => 65000,
            'duration_months' => 4,
        ],
        'rice' => [
            'name' => 'Rice',
            'seed_cost' => 60000,
            'fertilizer_cost' => 150000,
            'pesticide_cost' => 35000,
            'labor_cost' => 100000,
            'yield_per_acre' => 30,
            'price_per_unit' => 80000,
            'duration_months' => 5,
        ],
        'coffee' => [
            'name' => 'Coffee',
            'seed_cost' => 80000,
            'fertilizer_cost' => 100000,
            'pesticide_cost' => 60000,
            'labor_cost' => 120000,
            'yield_per_acre' => 8,
            'price_per_unit' => 250000,
            'duration_months' => 12,
        ],
        'beans' => [
            'name' => 'Beans',
            'seed_cost' => 55000,
            'fertilizer_cost' => 90000,
            'pesticide_cost' => 20000,
            'labor_cost' => 70000,
            'yield_per_acre' => 12,
            'price_per_unit' => 180000,
            'duration_months' => 3,
        ],
    ];

    private $locationMultipliers = [
        'arusha' => 1.1,
        'dodoma' => 0.9,
        'mwanza' => 1.0,
        'dar' => 0.95,
        'mbeya' => 1.15,
        'morogoro' => 1.05,
    ];

    public function index()
    {
        $crops = array_keys($this->cropData);
        $locations = array_keys($this->locationMultipliers);
        $calculation = session('calculation');

        return view('farmer.business-coach', compact('crops', 'locations', 'calculation'));
    }

    public function calculate(Request $request)
    {
        $validated = $request->validate([
            'crop_type' => 'required|in:' . implode(',', array_keys($this->cropData)),
            'farm_size' => 'required|numeric|min:0.1|max:1000',
            'location' => 'required|in:' . implode(',', array_keys($this->locationMultipliers)),
        ]);

        $crop = $this->cropData[$validated['crop_type']];
        $multiplier = $this->locationMultipliers[$validated['location']];
        $size = $validated['farm_size'];

        // Calculate costs
        $costs = [
            'seeds' => round($crop['seed_cost'] * $size),
            'fertilizer' => round($crop['fertilizer_cost'] * $size),
            'pesticides' => round($crop['pesticide_cost'] * $size),
            'labor' => round($crop['labor_cost'] * $size),
        ];

        $totalCost = array_sum($costs);

        // Calculate revenue
        $yield = round($crop['yield_per_acre'] * $size * $multiplier, 1);
        $revenue = round($yield * $crop['price_per_unit']);
        $profit = $revenue - $totalCost;
        $roi = $totalCost > 0 ? round(($profit / $totalCost) * 100, 1) : 0;

        $calculation = [
            'crop_name' => $crop['name'],
            'farm_size' => $size,
            'location' => $validated['location'],
            'costs' => $costs,
            'total_cost' => $totalCost,
            'yield' => $yield,
            'revenue' => $revenue,
            'profit' => $profit,
            'roi' => $roi,
            'duration' => $crop['duration_months'],
        ];

        return redirect()->route('farmer.business-coach')
            ->with('calculation', $calculation);
    }
}
