<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, seed the roles table with default roles
        $roles = [
            ['name' => 'farmer', 'display_name' => 'Farmer', 'description' => 'Agricultural producers and growers', 'icon' => 'agriculture', 'color' => 'primary'],
            ['name' => 'buyer', 'display_name' => 'Buyer', 'description' => 'Product purchasers and retailers', 'icon' => 'shopping_cart', 'color' => 'blue'],
            ['name' => 'supplier', 'display_name' => 'Supplier', 'description' => 'Input and equipment suppliers', 'icon' => 'inventory', 'color' => 'orange'],
            ['name' => 'expert', 'display_name' => 'Expert', 'description' => 'Agricultural consultants and specialists', 'icon' => 'school', 'color' => 'purple'],
            ['name' => 'logistics', 'display_name' => 'Logistics', 'description' => 'Transport and delivery services', 'icon' => 'local_shipping', 'color' => 'green'],
            ['name' => 'admin', 'display_name' => 'Administrator', 'description' => 'Platform administrators', 'icon' => 'admin_panel_settings', 'color' => 'primary'],
        ];
        
        foreach ($roles as $role) {
            DB::table('roles')->insertOrIgnore($role);
        }
        
        // Migrate existing user roles to pivot table
        $users = DB::table('users')->whereNotNull('role')->get();
        
        foreach ($users as $user) {
            $roleId = DB::table('roles')->where('name', $user->role)->value('id');
            
            if ($roleId) {
                DB::table('user_roles')->insert([
                    'user_id' => $user->id,
                    'role_id' => $roleId,
                    'is_primary' => true,
                    'assigned_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        DB::table('user_roles')->truncate();
        DB::table('roles')->truncate();
    }
};
