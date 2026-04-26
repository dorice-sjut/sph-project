<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;

#[Fillable(['name', 'email', 'password', 'role', 'phone', 'location', 'avatar', 'bio', 'preferred_language', 'is_blockchain_registered', 'wallet_address', 'blockchain_trust_score', 'trust_tier', 'blockchain_total_transactions', 'blockchain_successful_deliveries', 'blockchain_failed_deliveries', 'blockchain_registered_at'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLES = ['farmer', 'buyer', 'supplier', 'expert', 'logistics', 'admin'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_blockchain_registered' => 'boolean',
            'blockchain_registered_at' => 'datetime',
            'blockchain_trust_score' => 'integer',
        ];
    }

    /**
     * Many-to-many relationship with roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
            ->withPivot('is_primary', 'assigned_at', 'user_id', 'role_id')
            ->withTimestamps()
            ->select('roles.id', 'roles.name', 'roles.display_name', 'roles.icon', 'roles.color');
    }

    /**
     * Get the primary role for this user
     */
    public function primaryRole(): ?Role
    {
        return $this->roles()->wherePivot('is_primary', true)->first()
            ?? $this->roles()->first();
    }

    /**
     * Check if user has a specific role (supports both old and new system)
     */
    public function hasRole(string $role): bool
    {
        // Check new pivot table system using a subquery to avoid ambiguity
        $hasPivotRole = \DB::table('user_roles')
            ->join('roles', 'user_roles.role_id', '=', 'roles.id')
            ->where('user_roles.user_id', $this->id)
            ->where('roles.name', $role)
            ->exists();
        
        if ($hasPivotRole) {
            return true;
        }
        
        // Fallback to old role column for backward compatibility
        return $this->role === $role;
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roles): bool
    {
        foreach ($roles as $role) {
            if ($this->hasRole($role)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Assign a role to the user
     */
    public function assignRole(string $roleName, bool $isPrimary = false): void
    {
        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return;
        }

        // If setting as primary, unset other primary roles first
        if ($isPrimary) {
            $this->roles()->updateExistingPivot(
                $this->roles()->pluck('id')->toArray(),
                ['is_primary' => false]
            );
        }

        $this->roles()->syncWithoutDetaching([
            $role->id => [
                'is_primary' => $isPrimary,
                'assigned_at' => now(),
            ]
        ]);

        // Update legacy role column for backward compatibility
        if ($isPrimary) {
            $this->update(['role' => $roleName]);
        }
    }

    /**
     * Remove a role from the user
     */
    public function removeRole(string $roleName): void
    {
        $role = Role::where('name', $roleName)->first();
        
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Switch primary role
     */
    public function switchRole(string $roleName): bool
    {
        if (!$this->hasRole($roleName)) {
            return false;
        }

        $role = Role::where('name', $roleName)->first();
        
        if (!$role) {
            return false;
        }

        // Unset all primary roles
        $this->roles()->updateExistingPivot(
            $this->roles()->pluck('id')->toArray(),
            ['is_primary' => false]
        );

        // Set new primary role
        $this->roles()->updateExistingPivot($role->id, ['is_primary' => true]);

        // Update legacy role column
        $this->update(['role' => $roleName]);

        return true;
    }

    /**
     * Get all role names as array
     */
    public function getRoleNames(): array
    {
        // Query role names directly to avoid relationship loading issues
        $names = DB::table('roles')
            ->join('user_roles', 'roles.id', '=', 'user_roles.role_id')
            ->where('user_roles.user_id', $this->id)
            ->pluck('roles.name')
            ->toArray();
        
        // Include legacy role if not already in list
        if ($this->role && !in_array($this->role, $names)) {
            $names[] = $this->role;
        }
        
        return $names;
    }

    public function isFarmer(): bool
    {
        return $this->hasRole('farmer');
    }

    public function isBuyer(): bool
    {
        return $this->hasRole('buyer');
    }

    public function isSupplier(): bool
    {
        return $this->hasRole('supplier');
    }

    public function isExpert(): bool
    {
        return $this->hasRole('expert');
    }

    public function isLogistics(): bool
    {
        return $this->hasRole('logistics');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function sales()
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }
}
