<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory;
    protected $primaryKey = 'menu_id';
    protected $guarded = [];

    /**
     * Get all of the roleMenu for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleMenu(): HasMany
    {
        return $this->hasMany(RoleMenu::class, 'menu_id', 'menu_id');
    }

    /**
     * Get all of the rolePermissions for the Menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rolePermissions(): HasMany
    {
        return $this->hasMany(RolePermission::class, 'menu_id', 'menu_id');
    }
}
