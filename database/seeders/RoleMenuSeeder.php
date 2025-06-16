<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Menu;
use App\Models\RoleMenu;
use App\Models\User;
use Illuminate\Database\Seeder;

class RoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::first();
        
        // Clear existing role menu assignments to prevent duplicates
        RoleMenu::truncate();
        
        // Get roles
        $adminRole = Role::where('role_name', 'Admin')->first();
        $guruRole = Role::where('role_name', 'Guru')->first();
        $userRole = Role::where('role_name', 'User')->first();        // Get menus
        $dashboardMenu = Menu::where('menu_slug', 'dashboard')->first();
        $userMenu = Menu::where('menu_slug', 'user')->first();
        $roleMenu = Menu::where('menu_slug', 'role')->first();
        $menuMaster = Menu::where('menu_slug', 'menu')->first();
        $kelasMenu = Menu::where('menu_slug', 'lesson_package')->first();
        $keuanganMenu = Menu::where('menu_slug', 'keuangan')->first();
        $settingMenu = Menu::where('menu_slug', 'settings')->first();
        $attendanceMenu = Menu::where('menu_slug', 'attendance')->first();
        $profileMenu = Menu::where('menu_slug', 'profile')->first();
        $historyMenu = Menu::where('menu_slug', 'history_transaksi')->first();
        $homeMenu = Menu::where('menu_slug', 'home')->first();
        $logoutMenu = Menu::where('menu_slug', 'logout')->first();

        // Get child menus for attendance
        $attendanceMaster = Menu::where('menu_slug', 'attendance_master')->first();
        $attendanceGuru = Menu::where('menu_slug', 'attendance_guru')->first();

        // Get child menus for Master Menu
        $menuIndexChild = Menu::where('menu_slug', 'menu-index')->first();
        $menuCreateChild = Menu::where('menu_slug', 'menu-create')->first();

        // Get child menus for Keuangan 
        $keuanganLogChild = Menu::where('menu_slug', 'keuangan-log')->first();
        $keuanganTambahChild = Menu::where('menu_slug', 'keuangan-tambah')->first();
        $keuanganLaporanChild = Menu::where('menu_slug', 'laporan-keuangan')->first();
        $keuanganDashboardChild = Menu::where('menu_slug', 'keuangan-dashboard')->first();        // ROLE ADMIN - Akses ke semua menu master kecuali history transaksi dan attendance guru
        if ($adminRole) {
            $adminMenus = [
                $dashboardMenu,
                $userMenu,
                $roleMenu,
                $menuMaster,
                $kelasMenu,
                $keuanganMenu,
                $settingMenu,
                $attendanceMenu,
                $profileMenu,  // Admin bisa akses profile
                $homeMenu,
                $logoutMenu
                // Tidak termasuk: $historyMenu (history transaksi)
            ];

            foreach ($adminMenus as $menu) {
                if ($menu) {
                    RoleMenu::updateOrCreate(
                        [
                            'role_id' => $adminRole->role_id,
                            'menu_id' => $menu->menu_id,
                        ],
                        [
                            'created_by' => $admin->user_id,
                            'updated_by' => $admin->user_id,
                        ]
                    );
                }
            }

            // Admin bisa akses semua child menu kecuali attendance guru
            $adminChildMenus = [
                $attendanceMaster,  // Admin bisa kelola attendance
                // $attendanceGuru,  // TIDAK TERMASUK - Admin tidak bisa akses attendance guru
                $menuIndexChild,
                $menuCreateChild,
                $keuanganLogChild,
                $keuanganTambahChild,
                $keuanganLaporanChild,
                $keuanganDashboardChild
            ];

            foreach ($adminChildMenus as $childMenu) {
                if ($childMenu) {
                    RoleMenu::updateOrCreate(
                        [
                            'role_id' => $adminRole->role_id,
                            'menu_id' => $childMenu->menu_id,
                        ],
                        [
                            'created_by' => $admin->user_id,
                            'updated_by' => $admin->user_id,
                        ]
                    );
                }
            }
        }        // ROLE GURU - Hanya akses ke Attendance, Attendance Guru, Profile, Home, dan Logout
        if ($guruRole) {
            $guruMenus = [
                $attendanceMenu,  // Parent menu attendance
                $profileMenu,     // Guru bisa akses profile
                $homeMenu,
                $logoutMenu
            ];

            foreach ($guruMenus as $menu) {
                if ($menu) {
                    RoleMenu::updateOrCreate(
                        [
                            'role_id' => $guruRole->role_id,
                            'menu_id' => $menu->menu_id,
                        ],
                        [
                            'created_by' => $admin->user_id,
                            'updated_by' => $admin->user_id,
                        ]
                    );
                }
            }

            // Guru hanya bisa akses menu "Attendance Guru" (tidak bisa kelola attendance)
            if ($attendanceGuru) {
                RoleMenu::updateOrCreate(
                    [
                        'role_id' => $guruRole->role_id,
                        'menu_id' => $attendanceGuru->menu_id,
                    ],
                    [
                        'created_by' => $admin->user_id,
                        'updated_by' => $admin->user_id,
                    ]
                );
            }
        }        // ROLE USER - Hanya bisa akses Profile, History Transaksi, Home, dan Logout
        // User tidak memiliki akses ke halaman master sama sekali
        if ($userRole) {
            $userMenus = [
                $profileMenu,     // User bisa akses profile
                $historyMenu,     // User bisa akses history transaksi
                $homeMenu,        // User bisa akses home
                $logoutMenu       // User bisa logout
            ];

            foreach ($userMenus as $menu) {
                if ($menu) {
                    RoleMenu::updateOrCreate(
                        [
                            'role_id' => $userRole->role_id,
                            'menu_id' => $menu->menu_id,
                        ],
                        [
                            'created_by' => $admin->user_id,
                            'updated_by' => $admin->user_id,
                        ]
                    );
                }
            }
        }
    }
}