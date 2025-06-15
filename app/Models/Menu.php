<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    use HasFactory;
    protected $primaryKey = 'menu_id';
    protected $guarded = [];

    /**
     * Get the creator of the menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'user_id');
    }

    /**
     * Get the updater of the menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by', 'user_id');
    }

    /**
     * Get the parent menu
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_parent', 'menu_id');
    }

    /**
     * Get all children menus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children(): HasMany
    {
        return $this->hasMany(Menu::class, 'menu_parent', 'menu_id');
    }

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
