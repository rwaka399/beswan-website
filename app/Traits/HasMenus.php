<?php

namespace App\Traits;

use App\Models\Menu;

trait HasMenus
{
    /**
     * Get menus for master layout (admin menus)
     */
    protected function getMasterMenus()
    {
        return Menu::whereIn('menu_slug', [
            'dashboard', 'user', 'role', 'menu', 'kelas', 'keuangan', 'setting'
        ])
        ->whereNull('menu_parent')
        ->orderBy('menu_urutan')
        ->with('children')
        ->get();
    }

    /**
     * Get menus for profile layout (user menus)
     */
    protected function getProfileMenus()
    {
        return Menu::whereIn('menu_slug', [
            'profile', 'history_transaksi', 'home'
        ])
        ->whereNull('menu_parent')
        ->orderBy('menu_urutan')
        ->get();
    }
}
