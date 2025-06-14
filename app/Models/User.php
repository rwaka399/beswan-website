<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'province',
        'city',
        'kecamatan',
        'address',
        
        'created_by',
        'updated_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function userRole():HasMany
    {
        return $this->hasMany(UserRole::class, 'user_id', 'user_id');
    }

    // Relasi dengan Invoice
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'user_id', 'user_id');
    }

    public function isAdmin()
    {
        return $this->role && $this->role->role_name === 'Admin';
    }

    /**
     * Check if user has specific permission for a menu
     */
    public function hasPermission($menuSlug, $permission)
    {
        $userRole = $this->userRole()->with('role')->first();
        
        if (!$userRole || !$userRole->role) {
            return false;
        }
        
        return RolePermission::hasMenuPermission($userRole->role->role_id, $menuSlug, $permission);
    }

    /**
     * Check if user can access master data (admin/guru only)
     */
    public function canAccessMaster()
    {
        $userRole = $this->userRole()->with('role')->first();
        
        if (!$userRole || !$userRole->role) {
            return false;
        }
        
        $roleName = $userRole->role->role_name;
        return in_array($roleName, ['Admin', 'Guru']);
    }

    /**
     * Get user's role name
     */
    public function getRoleName()
    {
        $userRole = $this->userRole()->with('role')->first();
        
        if (!$userRole || !$userRole->role) {
            return null;
        }
        
        return $userRole->role->role_name;
    }
}
