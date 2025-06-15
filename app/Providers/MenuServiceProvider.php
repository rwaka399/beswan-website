<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */    public function boot(): void
    {
        // Share master menus with all master layout views
        View::composer('master.*', function ($view) {
            $menus = Menu::whereIn('menu_slug', [
                'dashboard', 'user', 'role', 'menu', 'kelas', 'keuangan', 'setting', 'logout'
            ])
            ->whereNull('menu_parent')
            ->orderBy('menu_urutan')
            ->with('children')
            ->get();

            $view->with('menus', $menus);
        });

        // Share profile menus with all profile layout views
        View::composer('profile.*', function ($view) {
            $menus = Menu::whereIn('menu_slug', [
                'profile', 'history_transaksi', 'home', 'logout'
            ])
            ->whereNull('menu_parent')
            ->orderBy('menu_urutan')
            ->get();

            $view->with('menus', $menus);
        });
    }
}
