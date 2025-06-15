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

        // Dashboard (single menu)
        Menu::create([
            'menu_name' => 'Dashboard',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-tachometer-alt',
            'menu_link' => '/master',
            'menu_urutan' => 1,
            'menu_parent' => null,
            'menu_slug' => 'dashboard',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Master User',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-users',
            'menu_link' => '/master/user',
            'menu_urutan' => 2,
            'menu_parent' => null,
            'menu_slug' => 'user',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Master Role',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-user-tag',
            'menu_link' => '/master/role',
            'menu_urutan' => 3,
            'menu_parent' => null,
            'menu_slug' => 'role',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Master Menu',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-bars',
            'menu_link' => '/master/role',
            'menu_urutan' => 4,
            'menu_parent' => null,
            'menu_slug' => 'menu',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Paket Les',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-chalkboard-teacher',
            'menu_link' => '/master/kelas',
            'menu_urutan' => 5,
            'menu_parent' => null,
            'menu_slug' => 'kelas',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        $financialLog = Menu::create([
            'menu_name' => 'Keuangan',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-money-bill-wave',
            'menu_link' => null,
            'menu_urutan' => 6,
            'menu_parent' => null,
            'menu_slug' => 'keuangan',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Log Keuangan',
            'menu_type' => 'child',
            'menu_icon' => null,
            'menu_link' => '/master/financial',
            'menu_urutan' => 1,
            'menu_parent' => $financialLog->menu_id,
            'menu_slug' => 'keuangan-log',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Tambah Log',
            'menu_type' => 'child',
            'menu_icon' => null,
            'menu_link' => '/master/financial/create',
            'menu_urutan' => 2,
            'menu_parent' => $financialLog->menu_id,
            'menu_slug' => 'keuangan-tambah',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Laporan Keuangan',
            'menu_type' => 'child',
            'menu_icon' => null,
            'menu_link' => '/master/financial/report',
            'menu_urutan' => 3,
            'menu_parent' => $financialLog->menu_id,
            'menu_slug' => 'laporan-keuangan',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Dashboard Keuangan',
            'menu_type' => 'child',
            'menu_icon' => null,
            'menu_link' => '/master/financial/dashboard',
            'menu_urutan' => 4,
            'menu_parent' => $financialLog->menu_id,
            'menu_slug' => 'keuangan-dashboard',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Setting',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-cog',
            'menu_link' => '/master/setting',
            'menu_urutan' => 7,
            'menu_parent' => null,
            'menu_slug' => 'setting',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

         $attendence = Menu::create([
            'menu_name' => 'Absensi',
            'menu_type' => 'parent',
            'menu_icon' => 'fas fa-check-circle',
            'menu_link' => null,
            'menu_urutan' => 9,
            'menu_parent' => null,
            'menu_slug' => 'attendence',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Attendence Master',
            'menu_type' => 'child',
            'menu_icon' => null,
            'menu_link' => '/master/attendece',
            'menu_urutan' => 1,
            'menu_parent' => $attendence->menu_id,
            'menu_slug' => 'attendence_master',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Attendence Guru',
            'menu_type' => 'child',
            'menu_icon' => null,
            'menu_link' => '/guru/attendece',
            'menu_urutan' => 2,
            'menu_parent' => $attendence->menu_id,
            'menu_slug' => 'attendence_guru    ',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Home',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-home',
            'menu_link' => '/home',
            'menu_urutan' => 10,
            'menu_parent' => null,
            'menu_slug' => 'home',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Profile',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-user',
            'menu_link' => '/profile',
            'menu_urutan' => 11,
            'menu_parent' => null,
            'menu_slug' => 'profile',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'History Transaksi',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-history',
            'menu_link' => '/profile/history',
            'menu_urutan' => 12,
            'menu_parent' => null,
            'menu_slug' => 'history_transaksi',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);

        Menu::create([
            'menu_name' => 'Logout',
            'menu_type' => 'main',
            'menu_icon' => 'fas fa-sign-out-alt',
            'menu_link' => '/logout',
            'menu_urutan' => 13,
            'menu_parent' => null,
            'menu_slug' => 'logout',
            'created_by' => $user->user_id,
            'updated_by' => $user->user_id,
        ]);
    }
}
