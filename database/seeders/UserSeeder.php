<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create users for each role
        $users = [
            [
                'name' => 'John Farmer',
                'email' => 'farmer@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'farmer',
                'phone' => '+255 712 345 678',
                'location' => 'Arusha, Tanzania',
                'bio' => 'Experienced maize and vegetable farmer with 10+ years of experience.',
            ],
            [
                'name' => 'Sarah Buyer',
                'email' => 'buyer@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'buyer',
                'phone' => '+255 723 456 789',
                'location' => 'Dar es Salaam, Tanzania',
                'bio' => 'Restaurant owner looking for fresh produce suppliers.',
            ],
            [
                'name' => 'Mike Supplier',
                'email' => 'supplier@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'supplier',
                'phone' => '+255 734 567 890',
                'location' => 'Moshi, Tanzania',
                'bio' => 'Agricultural inputs supplier - seeds, fertilizers, and equipment.',
            ],
            [
                'name' => 'Dr. Jane Expert',
                'email' => 'expert@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'expert',
                'phone' => '+255 745 678 901',
                'location' => 'Dodoma, Tanzania',
                'bio' => 'Agricultural scientist specializing in crop management and pest control.',
            ],
            [
                'name' => 'Tom Logistics',
                'email' => 'logistics@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'logistics',
                'phone' => '+255 756 789 012',
                'location' => 'Mwanza, Tanzania',
                'bio' => 'Cold chain logistics provider for agricultural products.',
            ],
            [
                'name' => 'Admin User',
                'email' => 'admin@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+255 767 890 123',
                'location' => 'Dar es Salaam, Tanzania',
                'bio' => 'Platform administrator.',
            ],
            // User with multiple roles for testing
            [
                'name' => 'Multi Role User',
                'email' => 'multi@agrosphere.com',
                'password' => Hash::make('password'),
                'role' => 'farmer',
                'phone' => '+255 778 901 234',
                'location' => 'Arusha, Tanzania',
                'bio' => 'Farmer who also buys supplies and sells to market.',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                $userData
            );
            
            // Assign role using the new system
            $user->assignRole($userData['role'], true);
        }

        // Assign multiple roles to the multi-role user
        $multiUser = User::where('email', 'multi@agrosphere.com')->first();
        if ($multiUser) {
            $multiUser->assignRole('buyer', false);
            $multiUser->assignRole('supplier', false);
        }

        // Create additional random users
        for ($i = 1; $i <= 10; $i++) {
            $role = ['farmer', 'buyer', 'supplier'][array_rand([0, 1, 2])];
            $user = User::firstOrCreate(
                ['email' => "user{$i}@example.com"],
                [
                    'name' => "User {$i}",
                    'email' => "user{$i}@example.com",
                    'password' => Hash::make('password'),
                    'role' => $role,
                    'phone' => '+255 7' . rand(10, 99) . ' ' . rand(100, 999) . ' ' . rand(100, 999),
                    'location' => ['Arusha', 'Dar es Salaam', 'Moshi', 'Dodoma', 'Mwanza'][array_rand([0, 1, 2, 3, 4])] . ', Tanzania',
                ]
            );
            
            // Assign role using the new system
            $user->assignRole($role, true);
        }
    }
}
