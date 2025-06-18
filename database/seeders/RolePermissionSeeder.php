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
        $userRole = Role::where('role_name', 'User')->first();        // ADMIN ROLE PERMISSIONS - Full CRUD access to all menus
        if ($adminRole) {
            $adminRoleMenus = RoleMenu::where('role_id', $adminRole->role_id)->with('menu')->get();
            
            foreach ($adminRoleMenus as $roleMenu) {
                if ($roleMenu->menu) {
                    $menu = $roleMenu->menu;
                    
                    // Admin has full CRUD permissions for all menus
                    $permissions = ['create', 'read', 'update', 'delete'];
                    
                    // Special cases for certain menus
                    if ($menu->menu_slug === 'dashboard' || $menu->menu_slug === 'home' || $menu->menu_slug === 'logout') {
                        // Dashboard, home, logout only need read access
                        $permissions = ['read'];
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
        }        // GURU ROLE PERMISSIONS - Limited CRUD access focused on attendance
        if ($guruRole) {
            $guruRoleMenus = RoleMenu::where('role_id', $guruRole->role_id)->with('menu')->get();
            
            foreach ($guruRoleMenus as $roleMenu) {
                if ($roleMenu->menu) {
                    $menu = $roleMenu->menu;
                    $permissions = [];
                    
                    if ($menu->menu_slug === 'attendance') {
                        // Parent attendance menu - only read
                        $permissions = ['read'];
                    } elseif ($menu->menu_slug === 'attendance_guru') {
                        // Guru can read and create their own attendance records
                        $permissions = ['read', 'create'];
                    } elseif ($menu->menu_slug === 'profile') {
                        // Profile - read and update
                        $permissions = ['read', 'update'];
                    } elseif ($menu->menu_slug === 'home' || $menu->menu_slug === 'logout') {
                        // Home page and logout - only read
                        $permissions = ['read'];
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
        }        // USER ROLE PERMISSIONS - Basic CRUD for profile and history
        if ($userRole) {
            $userRoleMenus = RoleMenu::where('role_id', $userRole->role_id)->with('menu')->get();
            
            foreach ($userRoleMenus as $roleMenu) {
                if ($roleMenu->menu) {
                    $menu = $roleMenu->menu;
                    $permissions = [];
                    
                    if ($menu->menu_slug === 'profile') {
                        // Profile - read and update
                        $permissions = ['read', 'update'];
                    } elseif ($menu->menu_slug === 'history_transaksi') {
                        // History - only read
                        $permissions = ['read'];
                    } elseif ($menu->menu_slug === 'home' || $menu->menu_slug === 'logout') {
                        // Home and logout - only read
                        $permissions = ['read'];
                    }
                    
                    foreach ($permissions as $permission) {
                        RolePermission::create([
                            'role_id' => $userRole->role_id,
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
        
        // echo "RolePermissionSeeder completed:\n";
        // echo "- Admin: " . RolePermission::where('role_id', $adminRole->role_id ?? 0)->count() . " permissions\n";
        // echo "- Guru: " . RolePermission::where('role_id', $guruRole->role_id ?? 0)->count() . " permissions\n";
        // echo "- User: " . RolePermission::where('role_id', $userRole->role_id ?? 0)->count() . " permissions (none as intended)\n";
    }
}