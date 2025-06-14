<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::first();
        
        // Master Data Menu
        $masterData = Menu::create([
            'menu_name' => 'Master Data',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-database',
            'menu_link' => '#',
            'menu_urutan' => 1,
            'menu_parent' => null,
            'menu_slug' => 'master-data',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // User Management
        Menu::create([
            'menu_name' => 'Users',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-users',
            'menu_link' => '/admin/users',
            'menu_urutan' => 1,
            'menu_parent' => $masterData->menu_id,
            'menu_slug' => 'users',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Role Management
        Menu::create([
            'menu_name' => 'Roles',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-user-tag',
            'menu_link' => '/admin/roles',
            'menu_urutan' => 2,
            'menu_parent' => $masterData->menu_id,
            'menu_slug' => 'roles',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Menu Management
        Menu::create([
            'menu_name' => 'Menus',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-list',
            'menu_link' => '/admin/menus',
            'menu_urutan' => 3,
            'menu_parent' => $masterData->menu_id,
            'menu_slug' => 'menus',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Lesson Package Management
        Menu::create([
            'menu_name' => 'Lesson Packages',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-book',
            'menu_link' => '/admin/lesson-packages',
            'menu_urutan' => 4,
            'menu_parent' => $masterData->menu_id,
            'menu_slug' => 'lesson-packages',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Course Management Menu
        $courseManagement = Menu::create([
            'menu_name' => 'Course Management',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-graduation-cap',
            'menu_link' => '#',
            'menu_urutan' => 2,
            'menu_parent' => null,
            'menu_slug' => 'course-management',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Courses
        Menu::create([
            'menu_name' => 'Courses',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-chalkboard-teacher',
            'menu_link' => '/courses',
            'menu_urutan' => 1,
            'menu_parent' => $courseManagement->menu_id,
            'menu_slug' => 'courses',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Lessons
        Menu::create([
            'menu_name' => 'Lessons',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-play-circle',
            'menu_link' => '/lessons',
            'menu_urutan' => 2,
            'menu_parent' => $courseManagement->menu_id,
            'menu_slug' => 'lessons',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Financial Management Menu
        $financialManagement = Menu::create([
            'menu_name' => 'Financial Management',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-dollar-sign',
            'menu_link' => '#',
            'menu_urutan' => 3,
            'menu_parent' => null,
            'menu_slug' => 'financial-management',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Invoices
        Menu::create([
            'menu_name' => 'Invoices',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-file-invoice',
            'menu_link' => '/invoices',
            'menu_urutan' => 1,
            'menu_parent' => $financialManagement->menu_id,
            'menu_slug' => 'invoices',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Financial Logs
        Menu::create([
            'menu_name' => 'Financial Logs',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-receipt',
            'menu_link' => '/financial-logs',
            'menu_urutan' => 2,
            'menu_parent' => $financialManagement->menu_id,
            'menu_slug' => 'financial-logs',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Reports Menu
        $reports = Menu::create([
            'menu_name' => 'Reports',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-chart-bar',
            'menu_link' => '#',
            'menu_urutan' => 4,
            'menu_parent' => null,
            'menu_slug' => 'reports',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // User Reports
        Menu::create([
            'menu_name' => 'User Reports',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-users',
            'menu_link' => '/reports/users',
            'menu_urutan' => 1,
            'menu_parent' => $reports->menu_id,
            'menu_slug' => 'user-reports',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Financial Reports
        Menu::create([
            'menu_name' => 'Financial Reports',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-chart-line',
            'menu_link' => '/reports/financial',
            'menu_urutan' => 2,
            'menu_parent' => $reports->menu_id,
            'menu_slug' => 'financial-reports',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Settings Menu
        $settings = Menu::create([
            'menu_name' => 'Settings',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-cog',
            'menu_link' => '#',
            'menu_urutan' => 5,
            'menu_parent' => null,
            'menu_slug' => 'settings',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // System Settings
        Menu::create([
            'menu_name' => 'System Settings',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-tools',
            'menu_link' => '/settings/system',
            'menu_urutan' => 1,
            'menu_parent' => $settings->menu_id,
            'menu_slug' => 'system-settings',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Profile Settings
        Menu::create([
            'menu_name' => 'Profile Settings',
            'menu_type' => 'child',
            'menu_icon' => 'fas fa-user-cog',
            'menu_link' => '/settings/profile',
            'menu_urutan' => 2,
            'menu_parent' => $settings->menu_id,
            'menu_slug' => 'profile-settings',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        // Dashboard (single menu)
        Menu::create([
            'menu_name' => 'Dashboard',
            'menu_type' => 'single',
            'menu_icon' => 'fas fa-tachometer-alt',
            'menu_link' => '/dashboard',
            'menu_urutan' => 0,
            'menu_parent' => null,
            'menu_slug' => 'dashboard',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);
    }
}
