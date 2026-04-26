<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => 'farmer',
                'display_name' => 'Farmer',
                'description' => 'Agricultural producers and growers who sell their produce',
                'icon' => 'agriculture',
                'color' => 'primary',
            ],
            [
                'name' => 'buyer',
                'display_name' => 'Buyer',
                'description' => 'Product purchasers, retailers, and restaurants',
                'icon' => 'shopping_cart',
                'color' => 'blue',
            ],
            [
                'name' => 'supplier',
                'display_name' => 'Supplier',
                'description' => 'Agricultural inputs, seeds, fertilizers, and equipment suppliers',
                'icon' => 'inventory',
                'color' => 'orange',
            ],
            [
                'name' => 'expert',
                'display_name' => 'Expert',
                'description' => 'Agricultural consultants, agronomists, and specialists',
                'icon' => 'school',
                'color' => 'purple',
            ],
            [
                'name' => 'logistics',
                'display_name' => 'Logistics',
                'description' => 'Transport, delivery, and cold chain service providers',
                'icon' => 'local_shipping',
                'color' => 'green',
            ],
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Platform administrators with full access',
                'icon' => 'admin_panel_settings',
                'color' => 'primary',
            ],
        ];

        foreach ($roles as $roleData) {
            Role::firstOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }
    }
}
