<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Schema;
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

    public function userRoles(): HasMany
    {
        return $this->hasMany(UserRole::class, 'user_id', 'user_id');
    }

    /**
     * Get the roles for this user through userRole relationship
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id', 'user_id', 'role_id');
    }

    /**
     * Get the primary role for this user
     */
    public function role(): ?Role
    {
        return $this->userRoles()->with('role')->first()?->role;
    }

    // Relasi dengan Invoice
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke UserLessonPackage
     */
    public function userLessonPackages()
    {
        return $this->hasMany(UserLessonPackage::class, 'user_id', 'user_id');
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->hasRole('Admin') || $this->user_id === 1;
    }

    /**
     * Check if user has specific permission for a menu
     */
    public function hasPermission($menuSlug, $permission)
    {
        $userRole = $this->userRoles()->with('role')->first();
        
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
        $userRole = $this->userRoles()->with('role')->first();
        
        if (!$userRole || !$userRole->role) {
            return false;
        }
        
        $roleName = $userRole->role->role_name;
        return in_array($roleName, ['Admin', 'Guru']);
    }

    /**
     * Check if user has specific role
     */
    public function hasRole($roleName)
    {
        // Use cached relationship if already loaded
        if ($this->relationLoaded('roles')) {
            return $this->roles->contains('role_name', $roleName);
        }
        
        return $this->roles()->where('role_name', $roleName)->exists();
    }

    /**
     * Get all role names for this user
     */
    public function getRoleNames()
    {
        return $this->roles()->pluck('role_name')->toArray();
    }

    /**
     * Get user's role name (first role if multiple)
     */
    public function getRoleName()
    {
        $userRole = $this->userRoles()->with('role')->first();
        
        if (!$userRole || !$userRole->role) {
            return null;
        }
        
        return $userRole->role->role_name;
    }

    /**
     * Mengecek apakah user saat ini berstatus premium
     */
    public function isPremium()
    {
        try {
            // Cek apakah tabel ada dan dapat diakses
            if (!Schema::hasTable('user_lesson_packages')) {
                return false;
            }
            
            $now = now();
            
            // Aktifkan paket yang sudah dijadwalkan dan sudah waktunya dimulai
            $scheduledPackages = $this->userLessonPackages()
                ->where('status', 'scheduled')
                ->where('scheduled_start_date', '<=', $now)
                ->get();
            
            foreach ($scheduledPackages as $package) {
                $package->status = 'active';
                $package->start_date = $now;
                $package->save();
            }
            
            // Cek status premium (hanya paket yang status aktif dan masih dalam periode aktif)
            return $this->userLessonPackages()
                ->where('status', 'active')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>', $now)
                ->exists();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error in isPremium method: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Mendapatkan paket premium aktif
     */
    public function getActivePremiumPackage()
    {
        return $this->userLessonPackages()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('lessonPackage')
            ->orderBy('end_date', 'desc')
            ->first();
    }

    /**
     * Mendapatkan semua paket premium aktif
     */
    public function getActivePremiumPackages()
    {
        return $this->userLessonPackages()
            ->where('status', 'active')
            ->where('end_date', '>', now())
            ->with('lessonPackage')
            ->orderBy('end_date', 'desc')
            ->get();
    }

    /**
     * Mendapatkan tanggal berakhir premium terjauh
     */
    public function getPremiumExpiryDate()
    {
        $package = $this->getActivePremiumPackage();
        return $package ? $package->end_date : null;
    }

    /**
     * Mendapatkan sisa hari premium
     */
    public function getRemainingPremiumDays()
    {
        $expiryDate = $this->getPremiumExpiryDate();
        return $expiryDate ? now()->diffInDays($expiryDate, false) : 0;
    }
}
