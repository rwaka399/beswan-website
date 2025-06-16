<?php

namespace App\Providers;

use App\Models\Menu;
use App\Models\User;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }    /**
     * Bootstrap any application services.
     */    public function boot(): void
    {
        // Share master menus with all master layout views
        View::composer('master.*', function ($view) {
            $user = auth()->user();
            $menus = collect();
            
            if ($user && Auth::guard('web')->check()) {
                // Get menu tree from session claimed by CustomSessionGuard
                $guard = Auth::guard('web');
                
                if ($guard instanceof \App\Guards\CustomSessionGuard) {
                    $sessionMenus = $guard->getUserMenus();
                    
                    // Convert session menu tree to Laravel collection format
                    $menus = collect($sessionMenus)->map(function ($menuData) {
                        $menu = (object) $menuData;
                        $menu->children = collect($menuData['children'] ?? [])->map(function ($childData) {
                            return (object) $childData;
                        });
                        return $menu;
                    });
                }
            }

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
