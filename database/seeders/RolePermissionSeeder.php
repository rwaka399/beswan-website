<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\RolePermission;
use App\Models\User;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        
        // Get roles
        $adminRole = Role::where('role_name', 'Admin')->first();
        $guruRole = Role::where('role_name', 'Guru')->first();
        $userRole = Role::where('role_name', 'User')->first();

        // ADMIN ROLE PERMISSIONS - Full access to all menus
        if ($adminRole) {
            $adminRoleMenus = RoleMenu::where('role_id', $adminRole->role_id)->with('menu')->get();
            
            foreach ($adminRoleMenus as $roleMenu) {
                if ($roleMenu->menu) {
                    $menu = $roleMenu->menu;
                    
                    // Admin has full CRUD permissions for all menus
                    $permissions = ['view', 'create', 'edit', 'delete'];
                    
                    // Special permissions for specific menus
                    if ($menu->menu_slug === 'attendance_master') {
                        // Admin can manage attendance sessions
                        $permissions = ['view', 'create', 'edit', 'delete', 'manage'];
                    } elseif ($menu->menu_slug === 'attendance_guru') {
                        // Admin can view and manage teacher attendance
                        $permissions = ['view', 'create', 'edit', 'delete', 'manage'];
                    } elseif ($menu->menu_slug === 'dashboard') {
                        // Dashboard only needs view
                        $permissions = ['view'];                    } elseif ($menu->menu_slug === 'settings') {
                        // Settings need special config permission
                        $permissions = ['view', 'edit', 'config'];
                    }
                    
                    foreach ($permissions as $permission) {
                        RolePermission::create([
                            'role_id' => $adminRole->role_id,
                            'menu_id' => $menu->menu_id,
                            'role_menu_id' => $roleMenu->role_menu_id,
                            'slug' => $permission,
                            'value' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // GURU ROLE PERMISSIONS - Limited access focused on attendance
        if ($guruRole) {
            $guruRoleMenus = RoleMenu::where('role_id', $guruRole->role_id)->with('menu')->get();
            
            foreach ($guruRoleMenus as $roleMenu) {
                if ($roleMenu->menu) {
                    $menu = $roleMenu->menu;
                    $permissions = [];
                    
                    if ($menu->menu_slug === 'attendance') {
                        // Parent attendance menu - only view
                        $permissions = ['view'];
                    } elseif ($menu->menu_slug === 'attendance_guru') {
                        // Guru can view and create their own attendance records
                        $permissions = ['view', 'create'];
                    } elseif ($menu->menu_slug === 'home') {
                        // Home page - only view
                        $permissions = ['view'];
                    } elseif ($menu->menu_slug === 'logout') {
                        // Logout - only execute
                        $permissions = ['execute'];
                    }
                    
                    foreach ($permissions as $permission) {
                        RolePermission::create([
                            'role_id' => $guruRole->role_id,
                            'menu_id' => $menu->menu_id,
                            'role_menu_id' => $roleMenu->role_menu_id,
                            'slug' => $permission,
                            'value' => true,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }

        // USER ROLE PERMISSIONS - No permissions for master menus
        // User role tidak memiliki RoleMenu, jadi tidak ada permission yang perlu dibuat
        // Mereka hanya bisa mengakses halaman profile yang terpisah dari sistem master
        
        echo "RolePermissionSeeder completed:\n";
        echo "- Admin: " . RolePermission::where('role_id', $adminRole->role_id ?? 0)->count() . " permissions\n";
        echo "- Guru: " . RolePermission::where('role_id', $guruRole->role_id ?? 0)->count() . " permissions\n";
        echo "- User: " . RolePermission::where('role_id', $userRole->role_id ?? 0)->count() . " permissions (none as intended)\n";
    }
}