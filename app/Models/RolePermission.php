<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RolePermission extends Model
{
    use HasFactory;
    protected $primaryKey = "role_permission_id";
    protected $guarded = [];

    /**
     * Get the role that owns the RolePermission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    /**
     * Get the menu that owns the RolePermission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }

    /**
     * Get the role menu that owns the RolePermission
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function roleMenu(): BelongsTo
    {
        return $this->belongsTo(RoleMenu::class, 'role_menu_id', 'role_menu_id');
    }

    public static function isHasPermission($role_id, $permission_slug)
    {
        $permission = RolePermission::where('role_id', $role_id)
            ->where('slug', $permission_slug)
            ->first();
        if ($permission && $permission->value === true) {
            return true;
        }
        return false;
    }

    /**
     * Check if user has permission for specific menu
     */
    public static function hasMenuPermission($role_id, $menu_slug, $permission_slug)
    {
        $permission = RolePermission::join('menus', 'role_permissions.menu_id', '=', 'menus.menu_id')
            ->where('role_permissions.role_id', $role_id)
            ->where('menus.menu_slug', $menu_slug)
            ->where('role_permissions.slug', $permission_slug)
            ->where('role_permissions.value', true)
            ->first();
            
        return $permission !== null;
    }

    /**
     * Get all permissions for a role and menu
     */
    public static function getRoleMenuPermissions($role_id, $menu_id)
    {
        return RolePermission::where('role_id', $role_id)
            ->where('menu_id', $menu_id)
            ->where('value', true)
            ->pluck('slug')
            ->toArray();
    }
}
